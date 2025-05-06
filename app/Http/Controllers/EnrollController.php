<?php

namespace App\Http\Controllers;

use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EnrollController extends Controller
{
    /**
     * Display the enrollment form view
     *
     * @return View
     */
    public function index()
    {
        $enrollments = Enroll::all();
        return view('inquiry.enroll', compact('enrollments'));
    }

    /**
     * Get enrollment data for DataTables
     *
     * @param Request $request
     * @param DataTables $dataTable
     * @return JsonResponse
     */
    // public function getData(Request $request, DataTables $dataTable): JsonResponse
    // {
    //     $enrolls = Enroll::select([
    //         DB::raw('CONCAT(parent_first_name, " ", parent_last_name) as parent_name'),
    //         DB::raw('CONCAT(student_first_name, " ", student_last_name) as student_name'),
    //         'parent_phone',
    //         'parent_email',
    //         'student_email',
    //         'school',
    //         'total_amount',
    //         'packages',
    //         'id' // Added for security in action links
    //     ]);

    //     return $dataTable->of($enrolls)
    //         ->addColumn('action', function(Enroll $enroll) {
    //             return view('components.actions.enroll-actions', [
    //                 'enroll' => $enroll
    //             ])->render();
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }
}