<?php

// app/Http/Controllers/TutorController.php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            'bio' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg',
        ]);

        try {
            // Handle file upload
            // $imagePath = $request->file('image')->store('tutors', 'public');

            // Create the Tutor profile
            $tutor = Tutor::create([
                'name' => $request->name,
                'designation' => $request->designation,
                'bio' => $request->bio,
                // 'image' => $imagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tutor profile created successfully.',
                'tutor' => $tutor,
            ]);
        } catch (\Exception $e) {
            Log::error('Tutor Store Error: ' . $e->getMessage());
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
    // public function edit(Tutor $tutor)
    // {
    //     $this->authorize('update', $tutor); // Ensure the tutor can edit their profile

    //     return view('tutors.edit', compact('tutor'));
    // }
    public function edit($id)
    {
        $tutor = Tutor::findOrFail($id);
        // $this->authorize('update', $tutor);
        return view('tutors.edit', compact('tutor'));
    }
    // Update tutor profile
    public function update(Request $request, Tutor $tutor)
    {
        // $this->authorize('update', $tutor);

        $validated = $request->validate([
            'name' => 'required|string',
            'designation' => 'required|string',
            'bio' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image
                if ($tutor->image) {
                    Storage::disk('public')->delete($tutor->image);
                }
                $validated['image'] = $request->file('image')->store('tutors', 'public');
            }

            $tutor->update($validated);

            return redirect()->route('tutors')->with('success', 'Tutor profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Tutor Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the tutor profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
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

