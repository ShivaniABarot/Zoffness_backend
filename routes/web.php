<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Satact_packagesController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\PraticeTestController;
use App\Http\Controllers\CollegeAdmissionController;
use App\Http\Controllers\CollegeEssaysController;
use App\Http\Controllers\ExecutiveCoachingController;
use App\Http\Controllers\SATACTCourseController;
use App\Http\Controllers\ExecutivePackageController;
use App\Http\Controllers\CollageEssaysPackageController;







/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// Register routes
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Forget password and reset password link send routes
Route::post('/forgot_password', [LoginController::class, 'showForgotPasswordForm'])->name('forgot_password');
Route::post('/forgot_password_link', [EmailController::class, 'sendPasswordResetLink'])->name('send_password_link');
Route::get('password/reset/{token}', [EmailController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [EmailController::class, 'resetPassword'])->name('password.update');
// Logout routes
Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
// Dashboard routes
// Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->name('dashboard')
    ->middleware('auth');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/recent-bookings', [DashboardController::class, 'recentBookings'])->name('recentBookings');
    Route::get('/all-bookings', [DashboardController::class, 'allRecentBookings'])->name('recentBookings.all');
    Route::get('/calendar/events', [DashboardController::class, 'events'])->name('calendar.events');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendar/events', [DashboardController::class, 'getEvents'])->name('calendar.events');
    Route::get('/calendar/enroll-bookings', [DashboardController::class, 'getEnrollBookings'])->name('calendar.enroll.bookings');
    Route::get('/calendar/events', [DashboardController::class, 'getEvents'])->name('calendar.events');
    Route::get('/calendar/bookings', [DashboardController::class, 'getBookingsByTypeAndDate'])->name('calendar.bookings');// profile routes
Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile')->middleware('auth');



// Route for listing all tutors
Route::get('tutors', [TutorController::class, 'index'])->name('tutors');  // List all tutors
Route::get('tutors/create', [TutorController::class, 'create'])->name('tutors.create');
Route::post('tutors', [TutorController::class, 'store'])->name('tutors.store');  // Store new tutor profile
Route::get('tutors/{tutor}', [TutorController::class, 'show'])->name('tutors.show');  // View single tutor profile
Route::get('tutors/{tutor}/edit', [TutorController::class, 'edit'])->name('tutors.edit');  // Show edit form for tutor profile
Route::post('tutors/{tutor}', [TutorController::class, 'update'])->name('tutors.update');  // Update tutor profile
Route::delete('/tutors/{id}', [TutorController::class, 'destroy'])->name('tutors.delete');


// // User Modules Routes
Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('users/store', [UsersController::class, 'store'])->name('users.store');
Route::get('users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::post('users/{id}/update', [UsersController::class, 'update'])->name('users.update');
Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users.delete');
Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');

// Student module routes
Route::get('/student', [StudentController::class, 'index'])->name('student');
Route::get('student/create', [StudentController::class, 'create'])->name('students.create');
Route::post('student/store', [StudentController::class, 'store'])->name('students.store');
Route::get('student/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::post('student/{id}/update', [StudentController::class, 'update'])->name('students.update');
Route::get('/student/{id}', [StudentController::class, 'show'])->name('students.show');
Route::delete('student/{id}', [StudentController::class, 'destroy'])->name('students.delete');


// Session module routes
Route::get('/session', [SessionController::class, 'index'])->name('sessions');
Route::get('session/create', [SessionController::class, 'create'])->name('sessions.create');
Route::post('session/store', [SessionController::class, 'store'])->name('sessions.store');
Route::get('session/{id}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
Route::post('session/{id}/update', [SessionController::class, 'update'])->name('sessions.update');
Route::get('/session/{id}', [SessionController::class, 'show'])->name('sessions.show');
Route::delete('session/{id}', [SessionController::class, 'destroy'])->name('sessions.delete');


// package satactcourse
Route::get('/sat_act_packages', [Satact_packagesController::class, 'index'])->name('satact_course.index');
Route::get('sat_act_packages/create', [Satact_packagesController::class, 'create'])->name('satact_course.create');
Route::post('sat_act_packages/store', [Satact_packagesController::class, 'store'])->name('satact_course.store');
Route::get('sat_act_packages/{id}/edit', [Satact_packagesController::class, 'edit'])->name('satact_course.edit');
Route::post('sat_act_packages/{id}/update', [Satact_packagesController::class, 'update'])->name(name: 'satact_course.update');
Route::get('/sat_act_packages/{id}', [Satact_packagesController::class, 'show'])->name('satact_course.show');
Route::delete('sat_act_packages/{id}', [Satact_packagesController::class, 'destroy'])->name('satact_course.delete');

//Package module routes
Route::get('/package', [PackageController::class, 'index'])->name('packages.index');
Route::get('package/create', [PackageController::class, 'create'])->name('packages.create');
Route::post('package/store', [PackageController::class, 'store'])->name('packages.store');
Route::get('package/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
Route::post('package/{id}/update', [PackageController::class, 'update'])->name('packages.update');
Route::get('/package/{id}', [PackageController::class, 'show'])->name('packages.show');
Route::delete('package/{id}', [PackageController::class, 'destroy'])->name('packages.delete');

//EXECUTIVE FUNCTION PACKAGES
Route::get('/executive_package', [ExecutivePackageController::class, 'index'])->name('executive_function_packages.index');
Route::get('executive_package/create', [ExecutivePackageController::class, 'create'])->name('executive_function_packages.create');
Route::post('executive_package/store', [ExecutivePackageController::class, 'store'])->name('executive_function_packages.store');
Route::get('executive_package/{id}/edit', [ExecutivePackageController::class, 'edit'])->name('executive_function_packages.edit');
Route::post('executive_package/{id}/update', [ExecutivePackageController::class, 'update'])->name('executive_function_packages.update');
Route::get('/executive_package/{id}', [ExecutivePackageController::class, 'show'])->name('executive_function_packages.show');
Route::delete('executive_package/{id}', [ExecutivePackageController::class, 'destroy'])->name('executive_function_packages.delete');

//College Essays Packages
Route::get('/collage_essays_packages', [CollageEssaysPackageController::class, 'index'])->name('collage_essays_packages.index');
Route::get('collage_essays_packages/create', [CollageEssaysPackageController::class, 'create'])->name('collage_essays_packages.create');
Route::post('collage_essays_packages/store', [CollageEssaysPackageController::class, 'store'])->name('collage_essays_packages.store');
Route::get('collage_essays_packages/{id}/edit', [CollageEssaysPackageController::class, 'edit'])->name('collage_essays_packages.edit');
Route::post('collage_essays_packages/{id}/update', [CollageEssaysPackageController::class, 'update'])->name('collage_essays_packages.update');
Route::get('/collage_essays_packages/{id}', [CollageEssaysPackageController::class, 'show'])->name('collage_essays_packages.show');
Route::delete('collage_essays_packages/{id}', [CollageEssaysPackageController::class, 'destroy'])->name('collage_essays_packages.delete');


// Payment module routes
Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/edit/{id}', [PaymentController::class, 'edit'])->name('payments.edit');
Route::post('/payments/update/{id}', [PaymentController::class, 'update'])->name('payments.update');
Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payments.show');
Route::delete('payment/{id}', [PaymentController::class, 'destroy'])->name('payments.delete');

// Exam form
Route::get('/examform', [ExamController::class, 'showForm'])->name('exam.form');
Route::post('/exam-checkout', [ExamController::class, 'createCheckoutSession'])->name('exam.checkout');
Route::get('/payment-success', [ExamController::class, 'success'])->name('payment.success');
Route::get('/payment-cancel', [ExamController::class, 'cancel'])->name('payment.cancel');


// Booking module routes
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/edit/{id}', [BookingController::class, 'edit'])->name('bookings.edit');
Route::post('/bookings/update/{id}', [BookingController::class, 'update'])->name('bookings.update');
Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
Route::delete('bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.delete');


// Inquiry->Enroll
Route::get('/inquiry/enroll', [EnrollController::class, 'index'])->name('enroll.list');

//Inquiry->Pratice test forms
Route::get('/inquiry/pratice_test', [PraticeTestController::class, 'index'])->name('pratice_test');
Route::get('/inquiry/college_admission',  [CollegeAdmissionController::class, 'index'])->name('collegeadmission.index');
Route::get('/inquiry/college_essays', [CollegeEssaysController::class, 'index'])->name('college_essays');
Route::get('/inquiry/executive_function', [ExecutiveCoachingController::class, 'index'])->name('executive_function');

Route::get('/sat_act_course', [SATACTCourseController::class, 'sat_act_course'])->name('sat_act_course');
