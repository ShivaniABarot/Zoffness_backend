<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    // list 
    public function index()
    {
        $sessions = Session::all();
        return view('sessions.index', compact('sessions'));
    }

    // create new session 
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'session_type' => 'required|in:study,exam,regular,extended_exam',
            'price_per_slot' => 'required|numeric',
            'max_capacity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        Session::create([
            'title' => $request->title,
            'session_type' => $request->session_type,
            'price_per_slot' => $request->price_per_slot,
            'max_capacity' => $request->max_capacity,
            'date' => $request->date,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('sessions')->with('success', 'Session created successfully.');
    }

    // edit and update 
    public function edit($id)
    {
        $session = Session::findOrFail($id);
        return view('sessions.edit', compact('session'));
    }

    // Update the specified user (update)
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'session_type' => 'required|in:study,exam,regular,extended_exam',
            'price_per_slot' => 'required|numeric',
            'max_capacity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('sessions.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $session = Session::findOrFail($id);
        $session->title = $request->title;
        $session->session_type = $request->session_type;
        $session->price_per_slot = $request->price_per_slot;
        $session->max_capacity = $request->max_capacity;
        $session->date = $request->date;
        $session->status = $request->status ?? 'active';

        $session->save();

        return response()->json([
            'success' => true,
            'message' => 'Session updated successfully.'
        ]);
    }


    public function show($id)
    {
        $session = Session::findOrFail($id);
        // dd($session);
        return view('sessions.view', compact('session'));
    }

    //ACTIVE INACTIVE BUTTON FUNCTION
    public function toggleStatus($id)
    {
        $session = Session::findOrFail($id);
        $session->status = $session->status === 'active' ? 'in-active' : 'active';
        $session->save();

        return response()->json([
            'success' => true,
            'message' => 'Session status updated successfully.',
            'new_status' => $session->status
        ]);
    }


    // Remove the specified user (delete)
    public function destroy($id)
    {
        // Find the session by its ID
        $session = Session::findOrFail($id);

        // Delete the session
        $session->delete();

        // Return a JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Session deleted successfully.',
        ]);
    }

    // GET SESSIONS API
    public function get_sessions()
    {
        $sessions = Session::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data' => $sessions
        ], 200);
    }



}
