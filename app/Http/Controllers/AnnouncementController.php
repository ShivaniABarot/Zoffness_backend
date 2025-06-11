<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('publish_at')
                      ->orWhere('publish_at', '<=', now());
            })
            ->latest('created_at')
            ->get();
    
        return view('announcements.index', compact('announcements'));
    }
    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'publish_at' => 'nullable|date',
        ]);

        Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'publish_at' => $request->publish_at,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json(['success' => false, 'message' => 'Announcement not found.']);
        }

        $announcement->delete();

        return response()->json(['success' => true, 'message' => 'Announcement deleted successfully.']);
    }
}
