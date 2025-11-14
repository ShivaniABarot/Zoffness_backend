<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsultationFee;
use Yajra\DataTables\Facades\DataTables;

class ConsultationController extends Controller
{
    // Show index page
    public function index()
    {
        return view('consultation.index');
    }

    // Get data for DataTable
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = ConsultationFee::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->status === 'active' ? 'checked' : '';
                    return '
                        <label class="switch">
                            <input type="checkbox" onchange="toggleStatus(' . $row->id . ', \'' . $row->status . '\')" ' . $checked . '>
                            <span class="slider"></span>
                        </label>
                    ';
                })
                ->addColumn('actions', function ($row) {
                    return '
                        <a href="' . route('consultation.edit', $row->id) . '" class=" me-1">
                            <i class="bx bx-edit"></i>
                        </a>
                        <a href="javascript:void(0);" onclick="deleteConsultation(' . $row->id . ')" class="">
                            <i class="bx bx-trash"></i>
                        </a>
                    ';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
    }

    // Show create form
    public function create()
    {
        return view('consultation.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        ConsultationFee::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('consultation.index')->with('success', 'Consultation added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $consultation = ConsultationFee::findOrFail($id);
        return view('consultation.edit', compact('consultation'));
    }

    // Update record
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $consultation = ConsultationFee::findOrFail($id);
        $consultation->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('consultation.index')->with('success', 'Consultation updated successfully.');
    }

    // Toggle active/inactive
    public function toggleStatus($id)
    {
        $fee = ConsultationFee::findOrFail($id);
        $fee->status = $fee->status === 'active' ? 'inactive' : 'active';
        $fee->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    // Delete record
    public function destroy($id)
    {
        $fee = ConsultationFee::find($id);
        if ($fee) {
            $fee->delete();
            return response()->json(['success' => true, 'message' => 'Consultation deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Consultation not found.']);
    }

    public function consultations(): JsonResponse
    {
        $ConsultationFee = ConsultationFee::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data'    => $ConsultationFee
        ], 200);

    }
}
