<?php

namespace App\Http\Controllers;

use App\Models\AboutZoffness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AboutZoffnessController extends Controller
{
    // Show index page
    public function index()
    {
        return view('about_zoffness.index');
    }

    /**
     * DataTable AJAX data source
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $about = AboutZoffness::select(['id', 'title', 'description', 'image_main', 'image_1', 'image_2', 'cta_text', 'cta_link'])->latest();
            return DataTables::of($about)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $view = '<a href="' . route('about-zoffness.show', $row->id) . '" title="View">
                                <i class="bi bi-eye"></i>
                             </a>';
                    $edit = '<a href="' . route('about-zoffness.edit', $row->id) . '" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                             </a>';
                    $delete = '<button onclick="deleteAbout(' . $row->id . ')" title="Delete">
                                <i class="bi bi-trash"></i>
                               </button>';
                    return $view . ' ' . $edit . ' ' . $delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Create form
    public function create()
    {
        return view('about_zoffness.create');
    }

    // Store about section
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable',
        ]);

        $data = $validated;

        // Upload images
        foreach (['image_main', 'image_1', 'image_2'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/about_images'), $imageName);
                $data[$field] = 'about_images/' . $imageName;
            }
        }

        AboutZoffness::create($data);

        return redirect()->route('about-zoffness.index')->with('success', 'About section added successfully!');
    }



    // Show single record
    // CHANGE THIS:
public function show(AboutZoffness $about_zoffness)
{
    return view('about_zoffness.show', compact('about_zoffness')); // ✅ Add compact()
}
    // Edit form
    public function edit(AboutZoffness $about_zoffness)
    {
        return view('about_zoffness.edit', compact('about_zoffness')); // ✅ Consistent variable name
    }
    // Update record
    public function update(Request $request, AboutZoffness $about_zoffness)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image_main' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable',
        ]);

        // Handle image updates
        foreach (['image_main', 'image_1', 'image_2'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old image
                if ($about_zoffness->$field && file_exists(public_path('storage/' . $about_zoffness->$field))) {
                    unlink(public_path('storage/' . $about_zoffness->$field));
                }

                $file = $request->file($field);
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/about_images'), $imageName);
                $about_zoffness->$field = 'about_images/' . $imageName;
            }
        }

        $about_zoffness->title = $validated['title'];
        $about_zoffness->description = $validated['description'];
        $about_zoffness->cta_text = $validated['cta_text'] ?? null;
        $about_zoffness->cta_link = $validated['cta_link'] ?? null;
        $about_zoffness->save();

        return redirect()->route('about-zoffness.index')->with('success', 'About section updated successfully!');
    }

    // Delete record
    public function destroy(AboutZoffness $about_zoffness)
    {
        foreach (['image_main', 'image_1', 'image_2'] as $field) {
            if ($about_zoffness->$field && file_exists(public_path('storage/' . $about_zoffness->$field))) {
                unlink(public_path('storage/' . $about_zoffness->$field));
            }
        }

        $about_zoffness->delete();
        return response()->json(['success' => true, 'message' => 'About section deleted successfully.']);
    }

    // API for frontend (fetch latest About section)
    public function about()
    {
        try {
            $about = AboutZoffness::all();

            return response()->json([
                'success' => true,
                'message' => 'About section retrieved successfully.',
                'data' => $about
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve about section.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
