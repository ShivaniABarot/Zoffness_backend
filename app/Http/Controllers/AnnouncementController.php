<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Mail\AnnouncementMail;
use Illuminate\Support\Facades\Mail;

class AnnouncementController extends Controller
{
    /**
     * Display all announcements.
     */
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_at' => 'nullable|date',
        ]);
    
        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'publish_at' => $request->publish_at,
            'is_active' => $request->is_active ? true : false,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully.'
        ]);
    }
    

    /**
     * Send the selected announcement to users.
     */
    public function sendAnnouncement(Request $request)
    {
        $announcement = Announcement::find($request->id);

        if (!$announcement) {
            return back()->with('error', 'Announcement not found.');
        }

        $recipients = User::where('receive_announcements', true)->pluck('email')->toArray();

        // Example fallback emails:
        // $recipients = ['shivanidbarot@gmail.com', 'dev@bugletech.com'];

        foreach ($recipients as $email) {
            Mail::to($email)->queue(new AnnouncementMail($announcement));
        }

        return back()->with('success', 'Announcement sent successfully!');
    }
}
