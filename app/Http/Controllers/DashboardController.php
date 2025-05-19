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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Session;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $recentBookings = $this->getRecentBookings();

        return view('dashboard', compact('recentBookings'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function index()
    {
        $studentCount = Student::count();
        $tutorCount = Tutor::count();

        try {
            $recentSessions = \App\Models\Session::orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($session) {
                    $status = optional($session->session_date)->isPast() ? 'Completed' : 'Upcoming';

                    return (object) [
                        'id' => $session->id,
                        'title' => $session->title,
                        'session_type' => $session->session_type,
                        'price_per_slot' => $session->price_per_slot,
                        'created_at' => $session->created_at,
                        'session_date' => $session->session_date,
                        'status' => $status
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error loading sessions: ' . $e->getMessage());
            $recentSessions = collect();
        }

        $recentBookings = $this->getRecentBookings();

        try {
            if (class_exists('\App\Models\Payment')) {
                $recentPayments = \App\Models\Payment::orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            } else {
                $recentPayments = collect();
            }
        } catch (\Exception $e) {
            Log::error('Error loading payments: ' . $e->getMessage());
            $recentPayments = collect();
        }

        return view('dashboard', compact(
            'studentCount',
            'tutorCount',
            'recentSessions',
            'recentBookings',
            'recentPayments'
        ));
    }

    // public function index()
    // {
    //     $recentBookings = $this->getRecentBookings();

    //     return view('dashboard', compact('recentBookings'));
    // }

    private function getRecentBookings()
    {
        $tables = [
            ['model' => SAT_ACT_Course::class, 'type' => 'SAT Prep Package', 'description_field' => 'package_name', 'sessions_default' => '10 Sessions', 'icon' => 'bx-book', 'status' => 'New'],
            ['model' => CollegeAdmission::class, 'type' => 'College Counseling', 'description_field' => 'packages', 'sessions_default' => '5 Sessions', 'icon' => 'bx-building-house', 'status' => 'Confirmed'],
            ['model' => CollegeEssays::class, 'type' => 'College Essay Review', 'description_field' => 'packages', 'sessions_default' => '3 Sessions', 'icon' => 'bx-edit', 'status' => 'Pending'],
            ['model' => Enroll::class, 'type' => 'Enroll', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
            ['model' => PraticeTest::class, 'type' => 'Pratice Test', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
            ['model' => ExecutiveCoaching::class, 'type' => 'Executive Coaching', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
        ];

        $bookings = collect();

        foreach ($tables as $entry) {
            if (class_exists($entry['model'])) {
                $data = $entry['model']::orderBy('created_at', 'desc')
                    ->take(3)
                    ->get()
                    ->map(function ($item) use ($entry) {
                        return [
                            'id' => $item->id,
                            'type' => $entry['type'],
                            'name' => $item->{$entry['description_field']} ?? 'N/A',
                            'student_name' => $item->student_first_name . ' ' . $item->student_last_name,
                            'sessions' => $item->sessions ?? $entry['sessions_default'],
                            'status' => $entry['status'],
                            'created_at' => $item->created_at,
                            'route' => strtolower(str_replace(' ', '_', $entry['type'])) . '.show',
                            'icon' => $entry['icon']
                        ];
                    });

                $bookings = $bookings->concat($data);
            }
        }

        return $bookings->sortByDesc('created_at')->take(5)->values();
    }


    public function getBookingsByTypeAndDate(Request $request)
{
    $type = $request->query('type');
    $date = $request->query('date');
    $bookingIds = $request->query('booking_ids', []);

    // Convert booking_ids to an array if it's a string
    if (is_string($bookingIds)) {
        $bookingIds = explode(',', $bookingIds);
        // Ensure the array contains only valid integers
        $bookingIds = array_filter($bookingIds, 'is_numeric');
        $bookingIds = array_map('intval', $bookingIds);
    }

    // If no valid booking IDs, return empty response to avoid query errors
    if (empty($bookingIds)) {
        return response()->json([]);
    }

    $tables = [
        ['model' => SAT_ACT_Course::class, 'type' => 'SAT Prep Package', 'description_field' => 'package_name', 'sessions_default' => '10 Sessions', 'icon' => 'bx-book', 'status' => 'New'],
        ['model' => CollegeAdmission::class, 'type' => 'College Counseling', 'description_field' => 'packages', 'sessions_default' => '5 Sessions', 'icon' => 'bx-building-house', 'status' => 'Confirmed'],
        ['model' => CollegeEssays::class, 'type' => 'College Essay Review', 'description_field' => 'packages', 'sessions_default' => '3 Sessions', 'icon' => 'bx-edit', 'status' => 'Pending'],
        ['model' => Enroll::class, 'type' => 'Enroll', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
        ['model' => PraticeTest::class, 'type' => 'Practice Test', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'], // Fixed typo
        ['model' => ExecutiveCoaching::class, 'type' => 'Executive Coaching', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
    ];

    $bookings = [];

    foreach ($tables as $entry) {
        if ($entry['type'] !== $type || !class_exists($entry['model'])) {
            continue;
        }

        $query = $entry['model']::whereIn('id', $bookingIds)
            // ->whereDate('start_date', $date)
            ->orWhereDate('created_at', $date)
            ->get();

        foreach ($query as $booking) {
            $bookings[] = [
                'id' => $booking->id,
                'type' => $entry['type'],
                'name' => $booking->{$entry['description_field']} ?? 'N/A',
                'student_name' => $booking->student_first_name . ' ' . $booking->student_last_name,
                'sessions' => $booking->sessions ?? $entry['sessions_default'],
                'status' => $entry['status'],
                'icon' => $entry['icon'],
            ];
        }
    }

    return response()->json($bookings);
}

    public function getEvents(Request $request)
    {
        $typeFilter = $request->query('type', null); // Get type filter from query parameter
        $tables = [
            ['model' => SAT_ACT_Course::class, 'type' => 'SAT Prep Package', 'description_field' => 'package_name', 'sessions_default' => '10 Sessions', 'icon' => 'bx-book', 'status' => 'New'],
            ['model' => CollegeAdmission::class, 'type' => 'College Counseling', 'description_field' => 'packages', 'sessions_default' => '5 Sessions', 'icon' => 'bx-building-house', 'status' => 'Confirmed'],
            ['model' => CollegeEssays::class, 'type' => 'College Essay Review', 'description_field' => 'packages', 'sessions_default' => '3 Sessions', 'icon' => 'bx-edit', 'status' => 'Pending'],
            ['model' => Enroll::class, 'type' => 'Enroll', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
            ['model' => PraticeTest::class, 'type' => 'Practice Test', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
            ['model' => ExecutiveCoaching::class, 'type' => 'Executive Coaching', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
        ];
    
        $events = [];
        $groupedEvents = [];
    
        foreach ($tables as $entry) {
            if ($typeFilter && $entry['type'] !== $typeFilter) {
                continue;
            }
    
            if (class_exists($entry['model'])) {
                $query = $entry['model']::orderBy('created_at', 'desc')->take(10);
                $bookings = $query->get();
    
                foreach ($bookings as $booking) {
                    $date =  $booking->created_at ?? null;
                    if (!$date) {
                        continue;
                    }
    
                    $dateKey = $date->toDateString();
                    $typeKey = $entry['type'];
    
                    // Group events by date and type
                    if (!isset($groupedEvents[$dateKey][$typeKey])) {
                        $groupedEvents[$dateKey][$typeKey] = [
                            'id' => $booking->id,
                            'title' => $entry['type'],
                            'start' => $dateKey,
                            'allDay' => true,
                            'extendedProps' => [
                                'type' => $entry['type'],
                                'booking_ids' => [$booking->id], // Store booking IDs for this type and date
                            ],
                            'icon' => $entry['icon'],
                        ];
                    } else {
                        // Add booking ID to existing group
                        $groupedEvents[$dateKey][$typeKey]['extendedProps']['booking_ids'][] = $booking->id;
                    }
                }
            }
        }
    
        // Flatten grouped events into a single array
        foreach ($groupedEvents as $date => $types) {
            foreach ($types as $event) {
                $events[] = $event;
            }
        }
    
        return response()->json($events);
    }
    public function getEnrollBookings(Request $request)
    {
        $date = $request->query('date');
        $type = $request->query('type');

        if (!$date || !$type || $type !== 'Enroll') {
            return response()->json([]);
        }

        $bookings = Enroll::whereDate('created_at', $date) // Adjust to your actual date field
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'Enroll',
                    'name' => $item->package_type ?? 'N/A',
                    'student_name' => $item->student_first_name . ' ' . $item->student_last_name,
                    'sessions' => $item->sessions ?? '15 Sessions',
                    'status' => 'Pending',
                    'icon' => 'bx-brain'
                ];
            });

        return response()->json($bookings);
        }







}
