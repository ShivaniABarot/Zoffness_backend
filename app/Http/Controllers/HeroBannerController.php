<?php

namespace App\Http\Controllers;

use App\Models\HeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
class HeroBannerController extends Controller
{
    // Show all hero banners
    public function index()
    {
        return view('hero_banners.index');
    }

    /**
     * DataTable AJAX data source.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $banners = HeroBanner::select(['id', 'tagline', 'background_image', 'cta_text', 'cta_link'])->latest();
    
            return DataTables::of($banners)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $editUrl = route('hero-banners.edit', $row->id);

$editBtn = "<a href='{$editUrl}' class='btn btn-sm btn-warning me-1' title='Edit'>
                <i class='bi bi-pencil-square'></i>
            </a>";
$deleteBtn = "<button onclick='deleteBanner({$row->id})' class='btn btn-sm btn-danger' title='Delete'>
                <i class='bi bi-trash'></i>
              </button>";

return "<div class='d-flex justify-content-start'>{$editBtn}{$deleteBtn}</div>";

                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }
    
    // Show form to create banner
    public function create()
    {
        return view('hero_banners.create');
    }

    

    // Store banner
    public function store(Request $request)
{
    $validated = $request->validate([
        'tagline' => 'required|string',
        'background_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'cta_text' => 'nullable|string',
        'cta_link' => 'nullable|url',
    ]);

    // Upload image
    if ($request->hasFile('background_image')) {
        $file = $request->file('background_image');
        $imageName = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('storage/hero_banners'), $imageName);
        $imagePath = 'hero_banners/'.$imageName;
    }
    

    HeroBanner::create([
        'tagline' => $validated['tagline'],
        'background_image' => $imagePath ?? null,
        'cta_text' => $validated['cta_text'] ?? null,
        'cta_link' => $validated['cta_link'] ?? null,
    ]);

    return redirect()->route('hero-banners.index')->with('success', 'Hero Banner created successfully!');
}


    // Edit form
    public function edit(HeroBanner $heroBanner)
    {
        return view('hero_banners.edit', ['banner' => $heroBanner]);
    }


    public function show(HeroBanner $heroBanner)
    {
        return view('hero-banners.show', compact('heroBanner'));
    }
    
    public function update(Request $request, HeroBanner $heroBanner)
    {
        $validated = $request->validate([
            'tagline' => 'required|string',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cta_text' => 'nullable|string',
            'cta_link' => 'nullable|url',
        ]);

        // Handle image upload if exists
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/hero_banners'), $imageName);
            $imagePath = 'hero_banners/'.$imageName;
        }
        

        $heroBanner->tagline = $validated['tagline'];
        $heroBanner->cta_text = $validated['cta_text'];
        $heroBanner->cta_link = $validated['cta_link'];
        $heroBanner->save();

        return redirect()->route('hero-banners.index')->with('success', 'Hero Banner updated successfully!');
    }
    // Delete
    public function destroy(HeroBanner $heroBanner)
    {
        $heroBanner->delete();
        return response()->json(['success' => true, 'message' => 'Banner deleted successfully.']);
    }

    // API FOR FRONTEND HERO BANNER
    
public function banners()
{
    try {
        $banners = HeroBanner::all();

        return response()->json([
            'success' => true,
            'message' => 'Hero banners retrieved successfully.',
            'data' => $banners
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve hero banners.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
