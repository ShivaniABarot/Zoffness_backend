<?php

namespace App\Http\Controllers;

use App\Models\MasterSATACTPage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class MasterSATACTPageController extends Controller
{
    /**
     * Display index page with DataTable
     */
    public function index()
    {
        return view('master_sat_act_page.index');
    }   

    /**
     * DataTable AJAX data source
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $records = MasterSATACTPage::select([
                'id',
                'section_type',
                'title',
                'subtitle',
                'description',
                'icon',
                'image_path',
                'point_text',
                'button_text',
                'button_link',
                'order_index',
                'status'
            ])->latest();

            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($row) {
                    if ($row->image_path) {
                        return '<img src="' . asset('storage/' . $row->image_path) . '" width="60" height="60" class="rounded">';
                    }
                    return '—';
                })
                ->addColumn('status_label', function ($row) {
                    return $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    $view = '<a href="' . route('master_sat_act_page.show', $row->id) . '" title="View">
                                <i class="bi bi-eye"></i>
                             </a>';
                    $edit = '<a href="' . route('master_sat_act_page.edit', $row->id) . '" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                             </a>';
                    $delete = '<button onclick="deleteRecord(' . $row->id . ')" title="Delete" class="btn btn-link text-danger p-0">
                                <i class="bi bi-trash"></i>
                               </button>';
                    return $view . ' ' . $edit . ' ' . $delete;
                })
                
                ->rawColumns(['actions', 'image_preview', 'status_label'])
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

  
    public function create()
{
    return view('master_sat_act_page.create');
}

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'section_type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'point_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('master_sat_act_page', 'public');
        }

        MasterSATACTPage::create($validated);
        return redirect()->route('master_sat_act_page.index')->with('success', 'SAT/ACT page content added successfully!');

        // return redirect()->route('master-sat-act-page.index')->with('success', 'SAT/ACT page content added successfully!');
    }

    /**
     * Show single record
     */
    public function show(MasterSATACTPage $masterSATACTPage)
    {
        return view('master_sat_act_page.show', compact('masterSATACTPage'));
    }

    /**
     * Show edit form
     */
    public function edit(MasterSATACTPage $masterSATACTPage)
    {
        $record = $masterSATACTPage;
        return view('master_sat_act_page.edit', compact('record'));
    }

    /**
     * Update record
     */
    public function update(Request $request, MasterSATACTPage $masterSATACTPage)
    {
        $validated = $request->validate([
            // 'section_type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'point_text' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($masterSATACTPage->image_path && Storage::disk('public')->exists($masterSATACTPage->image_path)) {
                Storage::disk('public')->delete($masterSATACTPage->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('master_sat_act_page', 'public');
        }

        $masterSATACTPage->update($validated);
        return redirect()->route('master_sat_act_page.index')->with('success', 'SAT/ACT page content updated successfully!');

        // return redirect()->route('master-sat-act-page.index')->with('success', 'SAT/ACT page content updated successfully!');
    }

    /**
     * Delete record
     */
    public function destroy(MasterSATACTPage $masterSATACTPage)
    {
        if ($masterSATACTPage->image_path && Storage::disk('public')->exists($masterSATACTPage->image_path)) {
            Storage::disk('public')->delete($masterSATACTPage->image_path);
        }

        $masterSATACTPage->delete();

        return response()->json(['success' => true, 'message' => 'SAT/ACT page content deleted successfully.']);
    }

    /**
     * API endpoint for frontend
     */
    public function getFrontendData()
    {
        try {
            $data = MasterSATACTPage::orderBy('order_index')->get();

            return response()->json([
                'success' => true,
                'message' => 'SAT/ACT page data retrieved successfully.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve SAT/ACT data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
