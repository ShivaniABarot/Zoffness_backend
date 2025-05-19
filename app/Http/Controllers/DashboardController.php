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

                    return (object)[
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
            ['model' => ExecutiveCoaching::class, 'type' => 'ACT Complete Prep', 'description_field' => 'package_type', 'sessions_default' => '15 Sessions', 'icon' => 'bx-brain', 'status' => 'Pending'],
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


    public function getEvents()
    {
        $tables = [
            ['model' => SAT_ACT_Course::class, 'type' => 'SAT Prep Package', 'description_field' => 'package_name', 'icon' => 'bx-book'],
            ['model' => CollegeAdmission::class, 'type' => 'College Counseling', 'description_field' => 'packages', 'icon' => 'bx-building-house'],
            ['model' => CollegeEssays::class, 'type' => 'College Essay Review', 'description_field' => 'packages', 'icon' => 'bx-edit'],
            ['model' => ExecutiveCoaching::class, 'type' => 'ACT Complete Prep', 'description_field' => 'package_type', 'icon' => 'bx-brain'],
        ];

        $events = [];

        foreach ($tables as $entry) {
            if (class_exists($entry['model'])) {
                $bookings = $entry['model']::orderBy('created_at', 'desc')->take(10)->get();

                foreach ($bookings as $booking) {
                    // Assume booking has a date or created_at field to use for calendar event date
                    $date = $booking->start_date ?? $booking->created_at ?? null; // Adjust field to actual date field
                    if (!$date) {
                        continue; // Skip if no date
                    }

                    $events[] = [
                        'id' => $booking->id,
                        'title' => $entry['type'] . ': ' . ($booking->{$entry['description_field']} ?? 'N/A'),
                        'start' => $date->toDateString(),
                        'allDay' => true,
                        'extendedProps' => [
                            'student_name' => $booking->student_first_name . ' ' . $booking->student_last_name,
                            'sessions' => $booking->sessions ?? '',
                            'type' => $entry['type'],
                        ],
                        'icon' => $entry['icon'],
                    ];
                }
            }
        }

        return response()->json($events);
    }














    

}
