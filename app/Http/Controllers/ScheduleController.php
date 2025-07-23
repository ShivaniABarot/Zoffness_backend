<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\ScheduleConfirmation;

class ScheduleController extends Controller
{
    public function schedule(Request $request)
{
    $request->validate([
        'name'             => 'required',
        'email'            => 'required',
        'phone_no'         => 'required',
        'date'             => 'required',
        'time_slot'        => 'required',
        'primary_interest' => 'required',
    ]);

    $data = $request->all();
    $data['fees'] = 199; 

    $schedule = Schedule::create($data);

    // Send confirmation email
    Mail::to($schedule->email)->send(new ScheduleConfirmation($schedule));

    return response()->json([
        'success' => true,
        'message' => 'Schedule created successfully.',
        'data'    => $schedule
    ], 201);
}

}
