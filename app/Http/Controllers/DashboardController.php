<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\SAT_ACT_Course;
use App\Models\CollegeAdmission;
use App\Models\CollegeEssays;
use App\Models\ExecutiveCoaching;
use App\Models\PraticeTest;
use App\Models\Enroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }


    public function index()
    {
        // Get counts for dashboard stats
        $studentCount = Student::count();
        $tutorCount = Tutor::count();

        // Get recent sessions (last 5)
        try {
            $recentSessions = \App\Models\Session::orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading sessions: ' . $e->getMessage());
            $recentSessions = collect(); // Empty collection if there's an error
        }

        // Get recent bookings from various tables
        $recentBookings = $this->getRecentBookings();

        // Get recent payments
        try {
            if (class_exists('\App\Models\Payment')) {
                $recentPayments = \App\Models\Payment::orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } else {
                $recentPayments = collect(); // Empty collection if Payment model doesn't exist
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading payments: ' . $e->getMessage());
            $recentPayments = collect(); // Empty collection if there's an error
        }

        return view('dashboard', compact(
            'studentCount',
            'tutorCount',
            'recentSessions',
            'recentBookings',
            'recentPayments'
        ));
    }

    /**
     * Get recent bookings from various tables
     */
    private function getRecentBookings()
    {
        $bookings = collect();

        // Get SAT/ACT Course bookings
        $satActBookings = SAT_ACT_Course::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'SAT Prep Package',
                    'name' => $item->package_name ?? 'SAT/ACT Course',
                    'student_name' => $item->student_first_name . ' ' . $item->student_last_name,
                    'sessions' => $item->sessions ?? '10 Sessions',
                    'status' => 'New',
                    'created_at' => $item->created_at,
                    'route' => 'sat_act_course.show',
                    'icon' => 'bx-book'
                ];
            });
        $bookings = $bookings->concat($satActBookings);

        // Get College Admission bookings
        $collegeAdmissionBookings = CollegeAdmission::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'College Counseling',
                    'name' => $item->packages ?? 'College Admission',
                    'student_name' => $item->student_first_name . ' ' . $item->student_last_name,
                    'sessions' => $item->sessions ?? '5 Sessions',
                    'status' => 'Confirmed',
                    'created_at' => $item->created_at,
                    'route' => 'college_admission.show',
                    'icon' => 'bx-building-house'
                ];
            });
        $bookings = $bookings->concat($collegeAdmissionBookings);

        // Get College Essays bookings
        $collegeEssaysBookings = CollegeEssays::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'College Essay Review',
                    'name' => $item->packages ?? 'College Essay',
                    'student_name' => $item->student_first_name . ' ' . $item->student_last_name,
                    'sessions' => $item->sessions ?? '3 Sessions',
                    'status' => 'Pending',
                    'created_at' => $item->created_at,
                    'route' => 'college_essays.show',
                    'icon' => 'bx-edit'
                ];
            });
        $bookings = $bookings->concat($collegeEssaysBookings);

        // Get Executive Coaching bookings
        $executiveCoachingBookings = ExecutiveCoaching::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'type' => 'ACT Complete Prep',
                    'name' => $item->package_type ?? 'Executive Coaching',
                    'student_name' => $item->student_first_name . ' ' . $item->student_last_name,
                    'sessions' => $item->sessions ?? '15 Sessions',
                    'status' => 'Pending',
                    'created_at' => $item->created_at,
                    'route' => 'executive_coaching.show',
                    'icon' => 'bx-brain'
                ];
            });
        $bookings = $bookings->concat($executiveCoachingBookings);

        // Sort by created_at and take the most recent 5
        return $bookings->sortByDesc('created_at')->take(5)->values();
    }


    public function recentBookings()
    {
        try {
            $tables = [
                [
                    'model' => SAT_ACT_Course::class,
                    'type' => 'SAT/ACT Course',
                    'description_field' => 'package_name'
                ],
                [
                    'model' => CollegeAdmission::class,
                    'type' => 'College Admission',
                    'description_field' => 'packages'
                ],
                [
                    'model' => CollegeEssays::class,
                    'type' => 'College Essay',
                    'description_field' => 'packages'
                ],
                [
                    'model' => ExecutiveCoaching::class,
                    'type' => 'Executive Function Coaching',
                    'description_field' => 'package_type'
                ],
                [
                    'model' => PraticeTest::class, // Fixed the typo in the model name
                    'type' => 'Practice Test',
                    'description_field' => 'test_type'
                ],
                [
                    'model' => Enroll::class,
                    'type' => 'Enrollment',
                    'description_field' => 'packages'
                ],
            ];

            $recentBookings = collect();

            foreach ($tables as $entry) {
                // Check if the model class exists
                if (class_exists($entry['model'])) {
                    $modelQuery = $entry['model']::where('created_at', '>=', now()->subDays(10))
                        ->latest('created_at')
                        ->take(10);

                    $data = $modelQuery->get()
                        ->map(function ($item) use ($entry) {
                            // Safely access the description field
                            $description = isset($item->{$entry['description_field']})
                                ? $item->{$entry['description_field']}
                                : 'No description available';

                            return [
                                'type' => $entry['type'],
                                'description' => $description,
                                'created_at' => $item->created_at->toIso8601String(),
                            ];
                        });

                    $recentBookings = $recentBookings->concat($data);
                } else {
                    Log::warning("Model not found: " . $entry['model']);
                }
            }

            $result = $recentBookings->sortByDesc('created_at')->take(10)->values()->all();

            Log::info('Recent bookings fetched successfully', ['count' => count($result)]);
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Error fetching recent bookings:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch recent bookings: ' . $e->getMessage()], 500);
        }
    }

    // Add a method for viewing all bookings
    public function allRecentBookings()
    {
        // This would be the view for displaying all bookings
        return view('bookings.all');
    }

}
