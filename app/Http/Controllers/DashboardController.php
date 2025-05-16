<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\SAT_ACT_course;
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
        $studentCount = Student::count();
        $tutorCount = Tutor::count();

        return view('dashboard', compact('studentCount', 'tutorCount'));
    }


    public function recentBookings()
    {
        try {
            $tables = [
                [
                    'model' => SAT_ACT_course::class,
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
