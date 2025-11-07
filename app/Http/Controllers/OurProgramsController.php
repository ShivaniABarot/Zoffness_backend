<?php

namespace App\Http\Controllers;

use App\Models\OurProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OurProgramsController extends Controller
{
    // Show index page
    public function index()
    {
        return view('programs.index');
    }

    /**
     * DataTable AJAX data source
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $programs = OurProgram::select(['id', 'title', 'short_description', 'icon_image', 'link'])->latest();
            return DataTables::of($programs)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $view = '<a href="' . route('programs.show', $row->id) . '" title="View">
                                <i class="bi bi-eye"></i>
                            </a>';
                    $edit = '<a href="' . route('programs.edit', $row->id) . '" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>';
                    $delete = '<button onclick="deleteProgram(' . $row->id . ')" title="Delete">
                                <i class="bi bi-trash"></i>
                               </button>';
                    return $view . ' ' . $edit . ' ' . $delete;
                })
                ->rawColumns(['actions', 'icon_image'])
                ->make(true);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Create form
    public function create()
    {
        return view('programs.create');
    }

    // Store program
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url',
        ]);

        // Upload image
        if ($request->hasFile('icon_image')) {
            $file = $request->file('icon_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/program_icons'), $imageName);
            $imagePath = 'program_icons/' . $imageName;
        }

        OurProgram::create([
            'title' => $validated['title'],
            'short_description' => $validated['short_description'],
            'icon_image' => $imagePath ?? null,
            'link' => $validated['link'] ?? null,
        ]);

        return redirect()->route('programs.index')->with('success', 'Program added successfully!');
    }

    // Edit form
    public function edit(OurProgram $program)
    {
        return view('programs.edit', ['program' => $program]);
    }

    // Show single program
    public function show(OurProgram $program)
    {
        return view('programs.show', compact('program'));
    }

    // Update program
    public function update(Request $request, OurProgram $program)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'icon_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('icon_image')) {
            // Delete old image
            if ($program->icon_image && file_exists(public_path('storage/' . $program->icon_image))) {
                unlink(public_path('storage/' . $program->icon_image));
            }

            $file = $request->file('icon_image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/program_icons'), $imageName);
            $imagePath = 'program_icons/' . $imageName;

            $program->icon_image = $imagePath;
        }

        $program->title = $validated['title'];
        $program->short_description = $validated['short_description'];
        $program->link = $validated['link'] ?? null;
        $program->save();

        return redirect()->route('programs.index')->with('success', 'Program updated successfully!');
    }

    // Delete program
    public function destroy(OurProgram $program)
    {
        if ($program->icon_image && file_exists(public_path('storage/' . $program->icon_image))) {
            unlink(public_path('storage/' . $program->icon_image));
        }

        $program->delete();
        return response()->json(['success' => true, 'message' => 'Program deleted successfully.']);
    }

    // API for frontend (fetch all programs)
    public function programs()
    {
        try {
            $programs = OurProgram::all();

            return response()->json([
                'success' => true,
                'message' => 'Programs retrieved successfully.',
                'data' => $programs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve programs.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
