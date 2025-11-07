<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    /**
     * Display index page with DataTable
     */
    public function index()
    {
        return view('testimonials.index');
    }

    /**
     * DataTable AJAX data source
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $testimonials = Testimonial::select(['id', 'name', 'email', 'relationship', 'rating', 'testimonial', 'consent'])
                ->latest();

            return DataTables::of($testimonials)
                ->addIndexColumn()
                ->addColumn('consent_status', function ($row) {
                    return $row->consent ? 'Yes' : 'No';
                })
                ->addColumn('actions', function ($row) {
                    $view = '<a href="' . route('testimonials.show', $row->id) . '" title="View">
                                <i class="bi bi-eye"></i>
                             </a>';
                    $edit = '<a href="' . route('testimonials.edit', $row->id) . '" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                             </a>';
                    $delete = '<button onclick="deleteTestimonial(' . $row->id . ')" title="Delete">
                                <i class="bi bi-trash"></i>
                               </button>';
                    return $view . ' ' . $edit . ' ' . $delete;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('testimonials.create');
    }

    /**
     * Store new testimonial
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'relationship' => 'nullable|string',
            'rating' => 'nullable|integer',
            'testimonial' => 'required|string',
            'consent' => 'nullable',
        ]);

        $data = $validated;
        $data['consent'] = $request->has('consent');

        Testimonial::create($data);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial added successfully!');
    }

    /**
     * Show single record
     */
    public function show(Testimonial $testimonial)
    {
        return view('testimonials.show', compact('testimonial'));
    }

    /**
     * Edit form
     */
    public function edit(Testimonial $testimonial)
    {
        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * Update record
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'relationship' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'testimonial' => 'required|string',
            'consent' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['consent'] = $request->has('consent');

        $testimonial->update($data);

        return redirect()->route('testimonials.index')->with('success', 'Testimonial updated successfully!');
    }

    /**
     * Delete record
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return response()->json(['success' => true, 'message' => 'Testimonial deleted successfully.']);
    }

    /**
     * API endpoint for frontend to fetch testimonials
     */
    public function testimonials()
    {
        try {
            $testimonials = Testimonial::all();

            return response()->json([
                'success' => true,
                'message' => 'Testimonials retrieved successfully.',
                'data' => $testimonials
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve testimonials.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
