<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Timeslot;
use App\Models\Session;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Show all bookings (Admin view)
    public function index()
    {
        $bookings = Booking::with(['session', 'timeslot', 'student', 'parent', 'package'])->get();
        return view('bookings.index', compact('bookings'));
    }

    // Show form to create a new booking
    public function create()
    {
        $sessions = Session::all();
        $timeslots = Timeslot::all();
        $packages = Package::all();
        $parents = User::where('role', 'parent')->get(); // Assuming 'parent' role
        return view('bookings.create', compact('sessions', 'timeslots', 'packages', 'parents'));
    }

    // Store a new booking
    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'timeslot_id' => 'required|exists:timeslots,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'package_id' => 'nullable|exists:packages,id',
        ]);

        // Fetch the package if provided
        $package = $request->package_id ? Package::find($request->package_id) : null;

        $booking = new Booking([
            'session_id' => $request->session_id,
            'timeslot_id' => $request->timeslot_id,
            'package_id' => $request->package_id,
            'status' => $request->status,
            'remaining_sessions' => $package ? $package->number_of_sessions : null,
        ]);

        $booking->save();

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }


    // Show a specific booking (for admin or parent)
    public function show($id)
    {
        $booking = Booking::with(['session', 'timeslot', 'package'])->findOrFail($id);
        return view('bookings.view', compact('booking'));
    }
    

    // Show form to edit a booking
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $sessions = Session::all();
        $timeslots = Timeslot::all();
        $packages = Package::all();
        $parents = User::where('role', 'parent')->get(); // Assuming 'parent' role
        return view('bookings.edit', compact('booking', 'sessions', 'timeslots', 'packages', 'parents'));
    }

    // Update a booking
    public function update(Request $request, $id)
    {
        $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'timeslot_id' => 'required|exists:timeslots,id',
            'status' => 'required|in:pending,confirmed,cancelled',
            'package_id' => 'nullable|exists:packages,id',
        ]);

        $booking = Booking::findOrFail($id);
        $package = $request->package_id ? Package::find($request->package_id) : null;

        $booking->update([
            'session_id' => $request->session_id,
            'timeslot_id' => $request->timeslot_id,
            'package_id' => $request->package_id,
            'status' => $request->status,
            'remaining_sessions' => $package ? $package->number_of_sessions : null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
    }

    // Delete a booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }
}
