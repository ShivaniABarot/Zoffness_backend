<?php

// app/Http/Controllers/TutorController.php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{

    public function index()
    {
        $tutors = Tutor::all();  // You can add pagination if needed
        return view('tutors.index', compact('tutors'));
    }

    // Display tutor profile form
    public function create()
    {
        return view('tutors.create');
    }

    // Store tutor profile

    public function store(Request $request)
    {
        // Validate the form inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg', 
            ]);

        try {
            // Handle file upload
            $imagePath = $request->file('image')->store('tutors', 'public');
            // dd($imagePath);
            // Create the Tutor profile
            $tutor = Tutor::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'designation' => $request->designation,
                'specialization' => $request->specialization,
                'bio' => $request->bio,
                'image' => $imagePath,
            ]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Tutor profile created successfully.',
                'tutor' => $tutor,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the tutor profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    // Show tutor profile
    public function show(Tutor $tutor)
    {
        return view('tutors.view', compact('tutor'));
    }

    // Edit tutor profile form
    public function edit(Tutor $tutor)
    {
        $this->authorize('update', $tutor); // Ensure the tutor can edit their profile

        return view('tutors.edit', compact('tutor'));
    }

    // Update tutor profile
    public function update(Request $request, Tutor $tutor)
    {
        $this->authorize('update', $tutor); // Ensure the tutor can update their profile

        $request->validate([
            'name' => 'required|string|max:255',  // Ensure 'name' is required
            'designation' => 'required|string|max:255',  // Validate designation
            'specialization' => 'required|string|max:255',  // Validate specialization
            'bio' => 'required|string',  // Validate bio
        ]);
        $tutor->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
        ]);

        return redirect()->route('tutors');
    }


    public function destroy($id)
    {
        // Find the tutor by ID
        $tutor = Tutor::find($id);

        if ($tutor) {
            // Delete the tutor record
            $tutor->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Tutor profile deleted successfully.',
            ]);
        }

        // If the tutor doesn't exist
        return response()->json([
            'success' => false,
            'message' => 'Tutor not found.',
        ], 404);
    }

}

