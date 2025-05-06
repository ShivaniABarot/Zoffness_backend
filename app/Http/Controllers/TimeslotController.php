<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use App\Models\Session;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class TimeslotController extends Controller
{
    // Show all timeslots
    public function index()
    {
        $timeslots = Timeslot::with('session')->get();
        return view('timeslots.index', compact('timeslots'));
    }

    // Show form to create a new timeslot
    public function create()
    {
        $sessions = Session::all(); // Fetch available sessions
        return view('timeslots.create', compact('sessions'));
    }

    // Store a new timeslot
    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'room' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'available_seats' => 'required|integer|min:1',
        ]);

        // Create the new timeslot
        Timeslot::create($validatedData);

        // Redirect back to timeslot list with success message
        return redirect()->route('timeslots.index')->with('success', 'Timeslot created successfully!');
    }

    // Show the form to edit an existing timeslot
    public function edit($id)
    {
        $timeslot = Timeslot::findOrFail($id);
        $sessions = Session::all(); // Fetch available sessions
        return view('timeslots.edit', compact('timeslot', 'sessions'));
    }

    // Update the timeslot

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:sessions,id',
            'room' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'available_seats' => 'required|integer|min:1',
        ]);

        // if ($validator->fails()) {
        //     return redirect()->route('timeslots.edit', $id)
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        // Find the timeslot to update
        $timeslot = Timeslot::findOrFail($id);
        $timeslot->session_id = $request->session_id;
        $timeslot->room = $request->room;
        $timeslot->date = $request->date;
        $timeslot->start_time = $request->start_time;
        $timeslot->end_time = $request->end_time;
        $timeslot->available_seats = $request->available_seats;
        $timeslot->save();

        return redirect()->route('timeslots.index')->with('success', 'Timeslot updated successfully.');
    }
    public function show($id)
    {
        // Fetch the timeslot with its associated session using Eloquent's relationship
        $timeslot = Timeslot::findOrFail($id);
    
        // Fetch the session title using the relationship defined in the Timeslot model
        $session = $timeslot->session;  // Assuming you have defined the relationship correctly in the Timeslot model
        // dd($session,$timeslot);
        return view('timeslots.view', compact('timeslot', 'session'));
    }
    

    // Delete a timeslot
    public function destroy($id)
    {
        $timeslot = Timeslot::findOrFail($id);
        $timeslot->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Timeslot deleted successfully.',
        ]);
    }
  
}
