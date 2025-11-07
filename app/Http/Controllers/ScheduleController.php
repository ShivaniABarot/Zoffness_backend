<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleConfirmation;

class ScheduleController extends Controller
{

    public function index()
    {
        try {
            $schedules = Schedule::all();
            return view('inquiry.schedule_consultation', compact('schedules'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retrieve schedules: ' . $e->getMessage());
        }
    }

    public function schedule(Request $request)
{
    $request->validate([
        'name'             => 'required',
        'email'            => 'required|email',
        'phone_no'         => 'required',
        'date'             => 'required',
        'time_slot'        => 'required',
        'primary_interest' => 'required',
    ]);

    $data = $request->all();
    $data['fees'] = 199;

    $schedule = Schedule::create($data);

    // Parent / user name
    $parentName = $request->name; // or you can split first/last if needed

    // Send email to user
    Mail::to($schedule->email)->queue(new ScheduleConfirmation(
        $parentName,
        $schedule->email,
        $schedule->phone_no,
        $schedule->date,
        $schedule->time_slot,
        $schedule->primary_interest,
        $schedule->fees,
        'user'
    ));

    // Admin emails
    $adminEmails = [
        'ben.hartman@zoffnesscollegeprep.com',
        'info@zoffnesscollegeprep.com',
        'dev@bugletech.com'
    ];

    // Send email to admins with parent name displayed
    Mail::to($adminEmails)->send(
        (new ScheduleConfirmation(
            $parentName,
            $schedule->email,
            $schedule->phone_no,
            $schedule->date,
            $schedule->time_slot,
            $schedule->primary_interest,
            $schedule->fees,
            'admin'
        ))
        ->from('web@notifications.zoffnesscollegeprep.com', $parentName)
        ->replyTo($schedule->email, $parentName)
    );

    return response()->json([
        'success' => true,
        'message' => 'Schedule created successfully.',
        'data'    => $schedule
    ], 201);
}


    // public function schedule(Request $request)
    // {
    //     $request->validate([
    //         'name'             => 'required',
    //         'email'            => 'required|email',
    //         'phone_no'         => 'required',
    //         'date'             => 'required',
    //         'time_slot'        => 'required',
    //         'primary_interest' => 'required',
    //     ]);

    //     $data = $request->all();
    //     $data['fees'] = 199;

    //     $schedule = Schedule::create($data);

    //     // Send email to user
    //     Mail::to($schedule->email)->queue(new ScheduleConfirmation(
    //         $schedule->name,
    //         $schedule->email,
    //         $schedule->phone_no,
    //         $schedule->date,
    //         $schedule->time_slot,
    //         $schedule->primary_interest,
    //         $schedule->fees,
    //         'user'
    //     ));

    //     // Send email to admins
    //     $adminEmails = [
    //         'ben.hartman@zoffnesscollegeprep.com',
    //         'info@zoffnesscollegeprep.com',
    //         'dev@bugletech.com'
    //     ];

    //     Mail::to($adminEmails)->queue(new ScheduleConfirmation(
    //         $schedule->name,
    //         $schedule->email,
    //         $schedule->phone_no,
    //         $schedule->date,
    //         $schedule->time_slot,
    //         $schedule->primary_interest,
    //         $schedule->fees,
    //         'admin'
    //     ));

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Schedule created successfully.',
    //         'data'    => $schedule
    //     ], 201);
    // }
}
