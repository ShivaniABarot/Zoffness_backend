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
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HeroBannerController;
use App\Http\Controllers\OurProgramsController;
use App\Http\Controllers\AboutZoffnessController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\OurApproachController;
use App\Http\Controllers\MediaVideoController;
use App\Http\Controllers\MasterSATACTPageController;
use App\Http\Controllers\ConsultationController;



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


Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');



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

// PROFILE ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile')->middleware('auth');


// Route for listing all tutors
Route::get('tutors', [TutorController::class, 'index'])->name('tutors');  
Route::get('tutors/create', [TutorController::class, 'create'])->name('tutors.create');
Route::post('tutors', [TutorController::class, 'store'])->name('tutors.store');  // Store new tutor profile
Route::get('tutors/{tutor}', [TutorController::class, 'show'])->name('tutors.show');  // View single tutor profile
Route::get('tutors/{tutor}/edit', [TutorController::class, 'edit'])->name('tutors.edit');  // Show edit form for tutor profile
Route::post('tutors/{tutor}', [TutorController::class, 'update'])->name('tutors.update');  // Update tutor profile
Route::delete('/tutors/{id}', [TutorController::class, 'destroy'])->name('tutors.delete');
Route::post('/tutors/status/{id}', [TutorController::class, 'toggleStatus'])->name('tutors.toggleStatus');

// // User Modules Routes
Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('/users/data', [UsersController::class, 'getUsers'])->name('users.data');
Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.delete');

// Route::get('/users', [UsersController::class, 'index'])->name('users');
Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
Route::post('users/store', [UsersController::class, 'store'])->name('users.store');
Route::get('users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
Route::post('users/{id}/update', [UsersController::class, 'update'])->name('users.update');
// Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users.delete');
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
Route::get('/session', [SessionController::class, 'index'])->name('sessions.index');
Route::get('/sessions/data', [SessionController::class, 'getSessions'])->name('sessions.data');
Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
Route::post('/sessions/store', [SessionController::class, 'store'])->name('sessions.store');
Route::get('/sessions/{id}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
Route::post('/sessions/{id}/update', [SessionController::class, 'update'])->name('sessions.update');
Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.delete');
Route::post('/sessions/{id}/toggle-status', [SessionController::class, 'toggleStatus'])->name('sessions.toggle-status');
Route::get('/sessions/{id}', [SessionController::class, 'show'])->name('sessions.show'); 

// package satactcourse
Route::get('/sat_act_packages', [Satact_packagesController::class, 'index'])->name('satact_course.index');
Route::get('sat_act_packages/data', [Satact_packagesController::class, 'getPackages'])->name('satact_course.data');
Route::get('sat_act_packages/create', [Satact_packagesController::class, 'create'])->name('satact_course.create');
Route::post('sat_act_packages/store', [Satact_packagesController::class, 'store'])->name('satact_course.store');
Route::get('sat_act_packages/{id}/edit', [Satact_packagesController::class, 'edit'])->name('satact_course.edit');
Route::delete('sat_act_packages/{id}', [Satact_packagesController::class, 'destroy'])->name('satact_course.delete');
Route::post('sat_act_packages/{id}/update', [Satact_packagesController::class, 'update'])->name('satact_course.update');
Route::post('/sat_act_packages/{id}/toggle-status', [Satact_packagesController::class, 'toggleStatus'])
    ->name('satact_course.toggleStatus');
Route::get('/sat_act_packages/{id}', [Satact_packagesController::class, 'show'])->name('satact_course.show');

// COLLEGE ADDMISSION PACKAGES
Route::get('/college_admission_package', [PackageController::class, 'index'])->name('packages.index');
Route::get('/college_admission_package/data', [PackageController::class, 'getPackages'])->name('packages.data');
Route::get('/college_admission_package/create', [PackageController::class, 'create'])->name('packages.create');
Route::post('/college_admission_package/store', [PackageController::class, 'store'])->name('packages.store');
Route::get('/college_admission_package/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
Route::post('/college_admission_package/{id}/update', [PackageController::class, 'update'])->name('packages.update');
Route::get('/college_admission_package/{id}', [PackageController::class, 'show'])->name('packages.show');
Route::delete('/college_admission_package/{id}', [PackageController::class, 'destroy'])->name('packages.delete');
Route::post('/college_admission_package/{id}/toggle-status', [PackageController::class, 'toggleStatus'])
    ->name('packages.toggleStatus');

//EXECUTIVE FUNCTION PACKAGES
Route::get('/executive_package', [ExecutivePackageController::class, 'index'])->name('executive_function_packages.index');
Route::get('/executive_function_packages/data', [ExecutivePackageController::class, 'getData'])->name('executive_function_packages.data');
Route::get('executive_package/create', [ExecutivePackageController::class, 'create'])->name('executive_function_packages.create');
Route::post('executive_package/store', [ExecutivePackageController::class, 'store'])->name('executive_function_packages.store');
Route::get('executive_package/{id}/edit', [ExecutivePackageController::class, 'edit'])->name('executive_function_packages.edit');
Route::post('executive_package/{id}/update', [ExecutivePackageController::class, 'update'])->name('executive_function_packages.update');
Route::get('/executive_package/{id}', [ExecutivePackageController::class, 'show'])->name('executive_function_packages.show');
Route::delete('executive_package/{id}', [ExecutivePackageController::class, 'destroy'])->name('executive_function_packages.delete');
Route::post('/executive_package/{id}/toggle-status', [ExecutivePackageController::class, 'toggleStatus'])
    ->name('executive_function_packages.toggleStatus');

//College Essays Packages
Route::get('/collage_essays_packages', [CollageEssaysPackageController::class, 'index'])->name('collage_essays_packages.index');
Route::get('/collage_essays_packages/data', [CollageEssaysPackageController::class, 'getData'])->name('collage_essays_packages.data');
Route::get('collage_essays_packages/create', [CollageEssaysPackageController::class, 'create'])->name('collage_essays_packages.create');
Route::post('collage_essays_packages/store', [CollageEssaysPackageController::class, 'store'])->name('collage_essays_packages.store');
Route::get('collage_essays_packages/{id}/edit', [CollageEssaysPackageController::class, 'edit'])->name('collage_essays_packages.edit');
Route::post('collage_essays_packages/{id}/update', [CollageEssaysPackageController::class, 'update'])->name('collage_essays_packages.update');
Route::get('/collage_essays_packages/{id}', [CollageEssaysPackageController::class, 'show'])->name('collage_essays_packages.show');
Route::delete('collage_essays_packages/{id}', [CollageEssaysPackageController::class, 'destroy'])->name('collage_essays_packages.delete');
Route::post('/collage_essays_packages/{id}/toggle-status', [CollageEssaysPackageController::class, 'toggleStatus'])
    ->name('collage_essays_packages.toggleStatus');


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


// Booking module routes sessions

Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/edit/{id}', [BookingController::class, 'edit'])->name('bookings.edit');
Route::post('/bookings/update/{id}', [BookingController::class, 'update'])->name('bookings.update');
Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
Route::delete('bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.delete');


// Inquiry->Enroll
Route::get('/inquiry', [InquiryController::class, 'index'])->name('inquiry.index');
Route::get('/inquiry/{type}/{id}', [InquiryController::class, 'show'])->name('inquiry.show');



Route::get('login/{provider}', [SocialLoginController::class, 'redirect']);
Route::get('login/{provider}/callback', [SocialLoginController::class, 'callback']);
Route::get('/login/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// announcements routes
Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
Route::post('/announcements/store', [AnnouncementController::class, 'store'])->name('announcements.store');
Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.delete');
Route::post('/send-announcement', [AnnouncementController::class, 'sendAnnouncement'])->name('send.announcement');
Route::post('/send-announcement', [AnnouncementController::class, 'sendAnnouncement'])->middleware('auth')
    ->name('send.announcement');

// Transaction routes
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

// LOGIN LOGS AND EMAIL LOGS 
Route::prefix('logs')->group(function () {
    Route::get('/', [LogController::class, 'index'])->name('logs.index');
    Route::get('/login-logs', [LogController::class, 'loginLogs'])->name('logs.login');
    Route::get('/email-logs', [LogController::class, 'emailLogs'])->name('logs.email');

    // Export routes
    Route::get('/export/login-logs/excel', [LogController::class, 'exportLoginLogsExcel'])->name('logs.login.export.excel');
    Route::get('/export/email-logs/excel', [LogController::class, 'exportEmailLogsExcel'])->name('logs.email.export.excel');
    Route::get('/export/login-logs/pdf', [LogController::class, 'exportLoginLogsPdf'])->name('logs.login.export.pdf');
    Route::get('/export/email-logs/pdf', [LogController::class, 'exportEmailLogsPdf'])->name('logs.email.export.pdf');
});

//Content Management -> Hero Banner routes
Route::get('/hero-banners', [HeroBannerController::class, 'index'])->name('hero-banners.index');
Route::get('/hero-banners/data', [HeroBannerController::class, 'getData'])->name('hero-banners.data');
Route::get('/hero-banners/create', [HeroBannerController::class, 'create'])->name('hero-banners.create');
Route::post('/hero-banners', [HeroBannerController::class, 'store'])->name('hero-banners.store');
Route::get('/hero-banners/{heroBanner}/edit', [HeroBannerController::class, 'edit'])->name('hero-banners.edit');
Route::put('/hero-banners/{heroBanner}', [HeroBannerController::class, 'update'])->name('hero-banners.update');
Route::delete('/hero-banners/{heroBanner}', [HeroBannerController::class, 'destroy'])->name('hero-banners.destroy');
Route::get('/hero-banners/{heroBanner}', [HeroBannerController::class, 'show'])
    ->name('hero-banners.show');

// Content Management -> Our Programs Routes
Route::get('/programs', [OurProgramsController::class, 'index'])->name('programs.index');
Route::get('/programs/data', [OurProgramsController::class, 'getData'])->name('programs.data');
Route::get('/programs/create', [OurProgramsController::class, 'create'])->name('programs.create');
Route::post('/programs', [OurProgramsController::class, 'store'])->name('programs.store');
Route::get('/programs/{program}', [OurProgramsController::class, 'show'])->name('programs.show');
Route::get('/programs/{program}/edit', [OurProgramsController::class, 'edit'])->name('programs.edit');
Route::put('/programs/{program}', [OurProgramsController::class, 'update'])->name('programs.update');
Route::delete('/programs/{program}', [OurProgramsController::class, 'destroy'])->name('programs.destroy');

// Content Management -> About zoffness Routes
Route::get('/about-zoffness', [AboutZoffnessController::class, 'index'])->name('about-zoffness.index');
Route::get('/about-zoffness/data', [AboutZoffnessController::class, 'getData'])->name('about-zoffness.data');
Route::get('/about-zoffness/create', [AboutZoffnessController::class, 'create'])->name('about-zoffness.create');
Route::post('/about-zoffness', [AboutZoffnessController::class, 'store'])->name('about-zoffness.store');
Route::get('/about-zoffness/{about_zoffness}', [AboutZoffnessController::class, 'show'])->name('about-zoffness.show');
Route::get('/about-zoffness/{about_zoffness}/edit', [AboutZoffnessController::class, 'edit'])->name('about-zoffness.edit');
Route::put('/about-zoffness/{about_zoffness}', [AboutZoffnessController::class, 'update'])->name('about-zoffness.update');
Route::delete('/about-zoffness/{about_zoffness}', [AboutZoffnessController::class, 'destroy'])->name('about-zoffness.destroy');

// Content Management -> Testimonial Routes
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::get('/testimonials/data', [TestimonialController::class, 'getData'])->name('testimonials.data');
Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
Route::get('/testimonials/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');
Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

// Content Management -> Our Approach Routes
Route::get('/our-approach', [OurApproachController::class, 'index'])->name('our-approach.index');
Route::get('/our-approach/data', [OurApproachController::class, 'getData'])->name('our-approach.data');
Route::get('/our-approach/create', [OurApproachController::class, 'create'])->name('our-approach.create');
Route::post('/our-approach', [OurApproachController::class, 'store'])->name('our-approach.store');
Route::get('/our-approach/{ourApproach}', [OurApproachController::class, 'show'])->name('our-approach.show');
Route::get('/our-approach/{ourApproach}/edit', [OurApproachController::class, 'edit'])->name('our-approach.edit');
Route::put('/our-approach/{ourApproach}', [OurApproachController::class, 'update'])->name('our-approach.update');
Route::delete('/our-approach/{ourApproach}', [OurApproachController::class, 'destroy'])->name('our-approach.destroy');

// Content Management -> Media Videos Routes
Route::get('/media-videos', [MediaVideoController::class, 'index'])->name('media-videos.index');
Route::get('/media-videos/data', [MediaVideoController::class, 'getData'])->name('media-videos.data');
Route::get('/media-videos/create', [MediaVideoController::class, 'create'])->name('media-videos.create');
Route::post('/media-videos', [MediaVideoController::class, 'store'])->name('media-videos.store');
Route::get('/media-videos/{mediaVideo}', [MediaVideoController::class, 'show'])->name('media-videos.show');
Route::get('/media-videos/{mediaVideo}/edit', [MediaVideoController::class, 'edit'])->name('media-videos.edit');
Route::put('/media-videos/{mediaVideo}', [MediaVideoController::class, 'update'])->name('media-videos.update');
Route::delete('/media-videos/{mediaVideo}', [MediaVideoController::class, 'destroy'])->name('media-videos.destroy');

// Content Management -> Master the SAT/ACT Page Routes

Route::get('/master_sat_act_page', [MasterSatActPageController::class, 'index'])->name('master_sat_act_page.index');
Route::get('/master_sat_act_page/create', [MasterSATACTPageController::class, 'create'])->name('master_sat_act_page.create');
Route::get('/master_sat_act_page/data', [MasterSATACTPageController::class, 'getData'])->name('master_sat_act_page.data');
// Route::post('/master_sat_act_page', [MasterSATACTPageController::class, 'store'])->name('master_sat_act_page.store');
Route::post('/master_sat_act_page', [MasterSATACTPageController::class, 'store'])
    ->name('master_sat_act_page.store');
Route::get('/master_sat_act_page/{masterSATACTPage}', [MasterSATACTPageController::class, 'show'])->name('master_sat_act_page.show');
Route::get('/master_sat_act_page/{masterSATACTPage}/edit', [MasterSATACTPageController::class, 'edit'])->name('master_sat_act_page.edit');
Route::put('/master_sat_act_page/{masterSATACTPage}', [MasterSATACTPageController::class, 'update'])->name('master_sat_act_page.update');
Route::delete('/master_sat_act_page/{masterSATACTPage}', [MasterSATACTPageController::class, 'destroy'])->name('master_sat_act_page.destroy');

// package - consultation (for change consultation package amount )
Route::prefix('consultation')->group(function () {
    Route::get('/', [ConsultationController::class, 'index'])->name('consultation.index');
    Route::get('/data', [ConsultationController::class, 'getData'])->name('consultation.data');
    Route::get('/create', [ConsultationController::class, 'create'])->name('consultation.create');
    Route::post('/store', [ConsultationController::class, 'store'])->name('consultation.store');
    Route::get('/edit/{id}', [ConsultationController::class, 'edit'])->name('consultation.edit');
    Route::post('/update/{id}', [ConsultationController::class, 'update'])->name('consultation.update');
    Route::post('/toggle-status/{id}', [ConsultationController::class, 'toggleStatus'])->name('consultation.toggleStatus');
    Route::delete('/delete/{id}', [ConsultationController::class, 'destroy'])->name('consultation.delete');
});