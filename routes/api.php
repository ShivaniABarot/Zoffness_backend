<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SATACTCourseController;
use App\Http\Controllers\PraticeTestController;
use App\Http\Controllers\ExecutiveCoachingController;
use App\Http\Controllers\CollegeAdmissionController;
use App\Http\Controllers\CollegeEssaysController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Satact_packagesController;
use App\Http\Controllers\ExecutivePackageController;
use App\Http\Controllers\CollageEssaysPackageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [LoginController::class, 'login']);
Route::post('/enroll', [EnrollController::class, 'new_enroll']);
Route::post('/new_sat_act', [SATACTCourseController::class, 'new_sat_act']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/practice_tests', [PraticeTestController::class, 'index']);
Route::post('/practice_tests', [PraticeTestController::class, 'store']);
Route::get('/practice_tests/{id}', [PraticeTestController::class, 'show']);
Route::put('/practice_tests/{id}', [PraticeTestController::class, 'update']);
Route::delete('/practice_tests/{id}', [PraticeTestController::class, 'destroy']);
Route::post('/executive_coaching', [ExecutiveCoachingController::class, 'store']);
Route::post('/college_admission', [CollegeAdmissionController::class, 'collage_addmission']);
Route::post('/college_essays', [CollegeEssaysController::class, 'college_essays']);
Route::get('/get_packages', [PackageController::class, 'get_packages']);
Route::get('/get_sessions', [SessionController::class, 'get_sessions']);
Route::get('/get_sat_act_packages', [Satact_packagesController::class, 'get_sat_act_packages']);
Route::get('/get_ExecutivePackage', [ExecutivePackageController::class, 'get_ExecutivePackage']);
Route::get('/get_CollageEssaysPackage', [CollageEssaysPackageController::class, 'get_CollageEssaysPackage']);
