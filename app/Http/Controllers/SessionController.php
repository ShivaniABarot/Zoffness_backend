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
        $sessions = Session::all();  // You can use pagination if needed
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
        ]);

        Session::create([
            'title' => $request->title,
            'session_type' => $request->session_type,
            'price_per_slot' => $request->price_per_slot,
            'max_capacity' => $request->max_capacity,
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
        $session->save();

        return redirect()->route('sessions')->with('success', 'Session updated successfully.');
    }

    public function show($id)
    {
        $session = Session::findOrFail($id); 
        // dd($session);
        return view('sessions.view', compact('session'));
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
    


}
