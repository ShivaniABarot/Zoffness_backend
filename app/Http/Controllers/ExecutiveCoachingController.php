<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ExecutiveCoaching;
use App\Mail\ExecutiveCoachingConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Stripe\Stripe;
use Illuminate\Support\Facades\DB;
class ExecutiveCoachingController extends Controller
{
    public function index()
    {
        $coaching = ExecutiveCoaching::all();
        return view('inquiry.executive_function', compact('coaching'));
    }

    public function store(Request $request)
    {
        // Set Stripe API key
        Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
    
        // Clean email addresses by removing timestamp prefix
        $studentEmail = preg_replace('/^\d+_/', '', $request->student_email);
        $parentEmail = $request->parent_email ? preg_replace('/^\d+_/', '', $request->parent_email) : null;
    
        // Validation
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string',
            'parent_last_name'  => 'nullable|string',
            'parent_phone'      => 'nullable|string',
            'parent_email'      => 'nullable|email',
            'student_first_name'=> 'required|string',
            'student_last_name' => 'required|string',
            'student_email'     => 'required|email',
            'school'            => 'required|string',
            'package_type'      => 'required', // allow ID or name
            'subtotal'          => 'required|numeric|min:0',
            'bank_name'         => 'nullable|string|max:255',
            'account_number'    => 'nullable|string|max:255',
            'exam_date'         => 'nullable|date',
            'stripe_id'         => 'nullable|string',
            'payment_status'    => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
    
        $totalAmount = (float) $request->subtotal;
        $parentName  = trim($request->parent_first_name . ' ' . $request->parent_last_name);
        $studentName = trim($request->student_first_name . ' ' . $request->student_last_name);
    
        // ✅ Safe lookup for package (by ID or name)
        $package = \App\Models\ExecutivePackage::where('id', $request->package_type)
            ->orWhere('name', $request->package_type)
            ->first();
    
        $packageName = $package ? $package->name : $request->package_type;
    
        // Parent details
        $parentDetails = [
            'name'  => $parentName,
            'phone' => $request->parent_phone,
            'email' => $parentEmail,
        ];
    
        // Stripe details (defaults)
        $stripeDetails = [
            'payment_method_type' => 'N/A',
            'last4'               => 'N/A',
            'status'              => 'N/A',
        ];
    
        if ($request->stripe_id) {
            try {
                $paymentIntent = PaymentIntent::retrieve($request->stripe_id);
                $stripeDetails = [
                    'payment_method_type' => $paymentIntent->payment_method_types[0] ?? 'N/A',
                    'last4'               => $paymentIntent->charges->data[0]->payment_method_details->card->last4 ?? 'N/A',
                    'status'              => $paymentIntent->status ?? 'N/A',
                ];
            } catch (\Exception $e) {
                \Log::error('Failed to fetch Stripe details: ' . $e->getMessage());
            }
        }
    
        try {
            $coaching = DB::transaction(function () use ($request, $parentName, $studentName, $totalAmount, $studentEmail, $parentEmail, $packageName) {
                $student = Student::create([
                    'student_email'  => $studentEmail,
                    'parent_name'    => $parentName,
                    'parent_phone'   => $request->parent_phone,
                    'parent_email'   => $parentEmail,
                    'student_name'   => $studentName,
                    'school'         => $request->school,
                    'bank_name'      => $request->bank_name,
                    'account_number' => $request->account_number,
                    'exam_date'      => $request->exam_date,
                    'amount'         => $totalAmount
                ]);
    
                return ExecutiveCoaching::create([
                    'parent_first_name'  => $request->parent_first_name,
                    'parent_last_name'   => $request->parent_last_name,
                    'parent_phone'       => $request->parent_phone,
                    'parent_email'       => $parentEmail,
                    'student_first_name' => $request->student_first_name,
                    'student_last_name'  => $request->student_last_name,
                    'student_email'      => $studentEmail,
                    'school'             => $request->school,
                    'package_id'         => $request->package_type, // can be ID
                    'package_type'       => $packageName,           // ✅ always store name
                    'subtotal'           => $totalAmount,
                    'exam_date'          => $request->exam_date,
                    'student_id'         => $student->id,
                    'stripe_id'          => $request->stripe_id
                ]);
            });
    
            // Send email to parent
            if (!empty($parentEmail)) {
                Mail::to($parentEmail)->send(new ExecutiveCoachingConfirmation(
                    $studentName,
                    $request->school,
                    $packageName,
                    $totalAmount,
                    $parentName,
                    'parent',
                    $request->exam_date,
                    $parentDetails,
                    $request->stripe_id,
                    $request->payment_status,
                    now()->format('m-d-Y'),
                    $stripeDetails,
                    $studentEmail
                ));
            }
    
            // Send email to student
            if (!empty($studentEmail)) {
                Mail::to($studentEmail)->send(new ExecutiveCoachingConfirmation(
                    $studentName,
                    $request->school,
                    $packageName,
                    $totalAmount,
                    $studentName,
                    'student',
                    $request->exam_date,
                    $parentDetails,
                    $request->stripe_id,
                    $request->payment_status,
                    now()->format('m-d-Y'),
                    $stripeDetails,
                    $studentEmail
                ));
            }
    
            // Send email to admins
            $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com'];
            $bccEmails   = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];
    
            Mail::to($adminEmails)
                ->bcc($bccEmails)
                ->send(
                    (new ExecutiveCoachingConfirmation(
                        $studentName,
                        $request->school,
                        $packageName,
                        $totalAmount,
                        'Admin',
                        'admin',
                        $request->exam_date,
                        $parentDetails,
                        $request->stripe_id,
                        $request->payment_status,
                        now()->format('m-d-Y'),
                        $stripeDetails,
                        $studentEmail
                    ))
                    ->from('web@notifications.zoffnesscollegeprep.com', $parentName)
                    ->replyTo($parentEmail, $parentName)
                );
    
            return response()->json([
                'success'        => true,
                'message'        => 'Executive Coaching registration created successfully',
                'data'           => $coaching,
                'payment_status' => $request->payment_status
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create record: ' . $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coaching = ExecutiveCoaching::find($id);

        if (!$coaching) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $coaching
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coaching = ExecutiveCoaching::find($id);

        if (!$coaching) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'sometimes|string|max:255',
            'parent_last_name' => 'sometimes|string|max:255',
            'parent_phone' => 'sometimes|string|max:20',
            'parent_email' => 'sometimes|email|max:255',
            'student_first_name' => 'sometimes|string|max:255',
            'student_last_name' => 'sometimes|string|max:255',
            'student_email' => 'sometimes|email|max:255',
            'school' => 'sometimes|string|max:255',
            'package_type' => 'sometimes|string|max:100',
            'subtotal' => 'sometimes|numeric|min:0',
            'exam_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $coaching->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $coaching
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coaching = ExecutiveCoaching::find($id);

        if (!$coaching) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $coaching->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully'
        ], 200);
    }
}
