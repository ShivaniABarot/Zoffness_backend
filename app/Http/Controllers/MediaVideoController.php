<?php

namespace App\Http\Controllers;

use App\Models\MediaVideo;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class MediaVideoController extends Controller
{
    /**
     * Display index page with DataTable
     */
    public function index()
    {
        return view('media_videos.index');
    }

    /**
     * DataTable AJAX data source
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $videos = MediaVideo::select(['id', 'title', 'video_url', 'description', 'is_active', 'order'])->latest();

            return DataTables::of($videos)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('actions', function ($row) {
                    $view = '<a href="' . route('media-videos.show', $row->id) . '" title="View">
                            <i class="bx bx-eye"></i>
                         </a>';
                    $edit = '<a href="' . route('media-videos.edit', $row->id) . '" title="Edit">
                            <i class="bx bx-edit"></i>
                         </a>';
                    $delete = '<button onclick="deleteVideo(' . $row->id . ')" title="Delete">
                            <i class="bx bx-trash"></i>
                           </button>';
                    return $view . ' ' . $edit . ' ' . $delete;
                })
                ->addColumn('video_preview', function ($row) {
                    return '<video width="80" height="50" controls class="rounded">
                                <source src="' . asset('storage' . $row->video_url) . '" type="video/mp4">
                            </video>';
                })
                ->rawColumns(['actions', 'status', 'video_preview'])
                ->make(true);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('media_videos.create');
    }

    /**
     * Store new record with VIDEO UPLOAD
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,mov,avi,wmv,flv|max:512000', 
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);
        // dd($request->all());
    
        // ✅ VIDEO UPLOAD - YOUR EXACT STYLE
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $videoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/zoffness_media'), $videoName);
            $videoPath = 'zoffness_media/' . $videoName;
        }
    
        $validated['video_url'] = $videoPath;
    
        MediaVideo::create($validated);
    
        return redirect()->route('media-videos.index')->with('success', 'Media Video uploaded successfully!');
    }

    /**
     * Show record
     */
    public function show(MediaVideo $mediaVideo)
    {
        return view('media_videos.show', compact('mediaVideo'));
    }

    /**
     * Show edit form
     */
    public function edit(MediaVideo $mediaVideo)
    {
        $video = $mediaVideo;
        return view('media_videos.edit', compact('video'));
    }

    /**
     * Update record with VIDEO UPLOAD
     */
    public function update(Request $request, MediaVideo $mediaVideo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);
    
        // ✅ VIDEO UPLOAD - EXACTLY LIKE YOUR background_image CODE
        if ($request->hasFile('video')) {
            // Delete old video
            if ($mediaVideo->video_url && file_exists(public_path('storage/' . $mediaVideo->video_url))) {
                unlink(public_path('storage/' . $mediaVideo->video_url));
            }
            
            // Upload new video
            $file = $request->file('video');
            $videoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/zoffness_media'), $videoName);
            $validated['video_url'] = 'zoffness_media/' . $videoName;
        }
    
        $mediaVideo->update($validated);
    
        return redirect()->route('media-videos.index')->with('success', 'Media Video updated successfully!');
    }
    /**
     * Delete record with VIDEO FILE
     */
    public function destroy(MediaVideo $mediaVideo)
    {
        // Delete video file
        if ($mediaVideo->video_url && Storage::disk('public')->exists(ltrim($mediaVideo->video_url, '/'))) {
            Storage::disk('public')->delete(ltrim($mediaVideo->video_url, '/'));
        }

        $mediaVideo->delete();

        return response()->json(['success' => true, 'message' => 'Media Video deleted successfully.']);
    }

    /**
     * API endpoint for frontend
     */
    public function media()
    {
        try {
            $data = MediaVideo::where('is_active', true)
                ->orderBy('order')
                ->get([
                    'id',
                    'title',
                    'video_url',
                    'description'
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Media Videos retrieved successfully.',
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