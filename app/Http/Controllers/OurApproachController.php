<?php

namespace App\Http\Controllers;

use App\Models\OurApproach;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class OurApproachController extends Controller
{
    /**
     * Display index page with DataTable
     */
    public function index()
    {
        return view('our_approach.index');
    }

    /**
     * DataTable AJAX data source
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $approaches = OurApproach::select(['id', 'section_title', 'description', 'image', 'highlights'])->latest();

            return DataTables::of($approaches)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset('storage/' . $row->image) . '" width="60" height="60" class="rounded">';
                    }
                    return '—';
                })
                ->addColumn('actions', function ($row) {
                    $view = '<a href="' . route('our-approach.show', $row->id) . '" title="View">
                                <i class="bi bi-eye"></i>
                             </a>';
                    $edit = '<a href="' . route('our-approach.edit', $row->id) . '" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                             </a>';
                    $delete = '<button onclick="deleteApproach(' . $row->id . ')" title="Delete">
                                <i class="bi bi-trash"></i>
                               </button>';
                    return $view . ' ' . $edit . ' ' . $delete;
                })
                ->rawColumns(['actions', 'image_preview'])
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('our_approach.create');
    }

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('our_approach', 'public');
        }

        $validated['highlights'] = json_encode($request->highlights);

        OurApproach::create($validated);

        return redirect()->route('our-approach.index')->with('success', 'Our Approach added successfully!');
    }


     
    public function show(OurApproach $ourApproach)
    {
        return view('our_approach.show', compact('ourApproach'));
    }
    

    public function edit(OurApproach $ourApproach)
    {
        $approach = $ourApproach;
        return view('our_approach.edit', compact('approach'));
    }
   
    /**
     * Update record
     */
    public function update(Request $request, OurApproach $ourApproach)
    {
        $validated = $request->validate([
            'section_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'highlights' => 'nullable|array',
            'highlights.*' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($ourApproach->image && Storage::disk('public')->exists($ourApproach->image)) {
                Storage::disk('public')->delete($ourApproach->image);
            }
            $validated['image'] = $request->file('image')->store('our_approach', 'public');
        }

        $validated['highlights'] = json_encode($request->highlights);

        $ourApproach->update($validated);

        return redirect()->route('our-approach.index')->with('success', 'Our Approach updated successfully!');
    }

    /**
     * Delete record
     */
    public function destroy(OurApproach $ourApproach)
    {
        if ($ourApproach->image && Storage::disk('public')->exists($ourApproach->image)) {
            Storage::disk('public')->delete($ourApproach->image);
        }

        $ourApproach->delete();

        return response()->json(['success' => true, 'message' => 'Our Approach deleted successfully.']);
    }

    /**
     * API endpoint for frontend
     */
    public function ourApproaches()
    {
        try {
            $data = OurApproach::all();

            return response()->json([
                'success' => true,
                'message' => 'Our Approaches retrieved successfully.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
