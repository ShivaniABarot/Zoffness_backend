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
        // Fetch only active tutors, optionally with pagination
        $tutors = Tutor::where('status', 'active')->orderBy('name')->get();

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bio' => 'required|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('tutors', 'public');
            }
            

            $tutor = Tutor::create($validated);

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


    public function toggleStatus(Request $request, $id)
{
    $tutor = Tutor::find($id);

    if (!$tutor) {
        return response()->json(['success' => false, 'message' => 'Tutor not found.'], 404);
    }

    $status = $request->input('status');
    if (!in_array($status, ['active', 'inactive'])) {
        return response()->json(['success' => false, 'message' => 'Invalid status.'], 422);
    }

    $tutor->status = $status;
    $tutor->save();

    return response()->json([
        'success' => true,
        'message' => "Tutor status changed to {$status} successfully."
    ]);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bio' => 'required|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
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


 //GET ALL TUTORS
 public function tutors()
{
    try {
        $tutors = \DB::table('tutors')
            ->where('status', 'active')
            ->orderByRaw("
                CASE
                    WHEN name = 'Ben Zoffness' THEN 1
                    WHEN name = 'Ben Hartman' THEN 2
                    ELSE 3
                END,
                name ASC
            ")
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Active tutors retrieved successfully.',
            'data' => $tutors,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch tutors.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}

