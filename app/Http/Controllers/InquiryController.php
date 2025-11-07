<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;
use App\Models\CollegeEssays;
use App\Models\ExecutiveCoaching;
use App\Models\Payment;
use App\Models\PraticeTest;
use App\Models\SAT_ACT_Course;

class InquiryController extends Controller
{
    /**
     * Fetch all inquiries from multiple tables
     */
        public function index(Request $request)
        {
            $bookingType = $request->input('booking_type');
            $examDate = $request->input('exam_date');

            // Map each booking source
            $collegeAdmissions = CollegeAdmission::all()->map(function ($item) {
                $item->display_booking_type = 'College Admission';
            
                // Detect active package
                if ($item->initial_intake == 1) {
                    $item->selected_package = 'Initial Intake';
                } elseif ($item->five_session_package == 1) {
                    $item->selected_package = 'Five Session Package';
                } elseif ($item->ten_session_package == 1) {
                    $item->selected_package = 'Ten Session Package';
                } elseif ($item->fifteen_session_package == 1) {
                    $item->selected_package = 'Fifteen Session Package';
                } elseif ($item->twenty_session_package == 1) {
                    $item->selected_package = 'Twenty Session Package';
                } else {
                    $item->selected_package = 'N/A';
                }
            
                return $item;
            });
            

            $collegeEssays = CollegeEssays::all()->map(function ($item) {
                $item->display_booking_type = 'College Essay';
            
                try {
                    $packages = $item->packages();
                    if ($packages->count() > 0) {
                        $item->selected_package = $packages->pluck('name')->implode(', ');
                        $item->loaded_packages = $packages;
                    } else {
                        $item->selected_package = 'N/A';
                        $item->loaded_packages = collect(); 
                    }
                } catch (\Exception $e) {
                    $item->selected_package = 'N/A';
                    $item->loaded_packages = collect();
                }
                // dd($item);
                return $item;
            });
            
            
            

            $executiveCoachings = ExecutiveCoaching::with('package')->get()->map(function ($item) {
                $item->display_booking_type = 'Executive Coaching';
                
                if ($item->package) {
                    $item->selected_package = $item->package->name;
                    $item->loaded_packages = collect([$item->package]);
                } else {
                    $item->selected_package = 'N/A';
                    $item->loaded_packages = collect();
                }
            
                return $item;
            });
            
            

            $practiceTests = PraticeTest::all()->map(function ($item) {
                $item->display_booking_type = 'Practice Test';
                return $item;
            });

            $satActCourses = SAT_ACT_Course::all()->map(function ($item) {
                $item->display_booking_type = 'SAT/ACT Course';
                return $item;
            });

            $payments = Payment::with('user')->get()->map(function ($item) {
                $item->display_booking_type = 'Payment';

                // Override parent name from User model
                if ($item->user) {
                    $item->parent_first_name = $item->user->firstname ?? '';
                    $item->parent_last_name = $item->user->lastname ?? '';
                }

                return $item;
            });

            // Merge all bookings
            $allBookings = collect()
                ->merge($collegeAdmissions)
                ->merge($collegeEssays)
                ->merge($executiveCoachings)
                ->merge($practiceTests)
                ->merge($satActCourses)
                ->merge($payments);

            // Filter by display booking type if requested
            if (!empty($bookingType)) {
                $allBookings = $allBookings->filter(function ($item) use ($bookingType) {
                    return $item->display_booking_type === $bookingType;
                });
            }

            // Filter by exam_date if requested
            if (!empty($examDate)) {
                try {
                    $parsedDate = Carbon::createFromFormat('d-m-Y', $examDate)->format('Y-m-d');
                    $allBookings = $allBookings->filter(function ($item) use ($parsedDate) {
                        // Check if exam_date exists and matches the parsed date
                        return isset($item->exam_date) && 
                            Carbon::parse($item->exam_date)->format('Y-m-d') === $parsedDate;
                    });
                } catch (\Exception $e) {
                    // Handle invalid date format gracefully (optional: log error or return message)
                    $allBookings = collect(); // Return empty collection if date is invalid
                }
            }

            // Sort by created_at descending
            $allBookings = $allBookings->sortByDesc('created_at');

            // Get unique booking types for dropdown
            $bookingTypes = $allBookings->pluck('display_booking_type')->unique();

            return view('inquiry.index', [
                'allBookings' => $allBookings,
                'bookingTypes' => $bookingTypes,
                'bookingType' => $bookingType
            ]);
        }
        

    // view details
    public function show($type, $id)
{
    switch ($type) {
        case 'college-admission':
            $item = CollegeAdmission::findOrFail($id);
            $title = "College Admission Inquiry";
            break;

        case 'college-essay':
            $item = CollegeEssays::findOrFail($id);
            $title = "College Essay Inquiry";
            break;

        case 'executive-coaching':
            $item = ExecutiveCoaching::findOrFail($id);
            $title = "Executive Coaching Inquiry";
            break;

        case 'practice-test':
            $item = PraticeTest::findOrFail($id);
            $title = "Practice Test Inquiry";
            break;

        case 'sat-act-course':
            $item = SAT_ACT_Course::findOrFail($id);
            $title = "SAT/ACT Course Inquiry";
            break;

        case 'payment':
            $item = Payment::with('user')->findOrFail($id);
            $title = "Payment Details";
            break;

        default:
            abort(404);
    }
    // dd($item);
    return view('inquiry.show', compact('item', 'title', 'type'));
}

    

}
