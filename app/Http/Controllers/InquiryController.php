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
    
        // Map each booking source
        $collegeAdmissions = CollegeAdmission::all()->map(function ($item) {
            $item->display_booking_type = 'College Admission';
            return $item;
        });
    
        $collegeEssays = CollegeEssays::all()->map(function ($item) {
            $item->display_booking_type = 'College Essay';
            return $item;
        });
    
        $executiveCoachings = ExecutiveCoaching::all()->map(function ($item) {
            $item->display_booking_type = 'Executive Coaching';
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
                $item->parent_last_name  = $item->user->lastname ?? '';
            }
        
            return $item;
        });

        
        // $payments = Payment::all()->map(function ($item) {
        //     $item->display_booking_type = 'Payment';
        //     return $item;
        // });
    
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
