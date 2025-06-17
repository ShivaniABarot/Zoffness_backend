<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Student;
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
     * Show the create form with students.
     */


    public function create()
{
    $students = Student::select('student_email')->distinct()->get();
    return view('announcements.create', compact('students'));
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'to_emails' => 'required|array',
        'to_emails.*' => 'email|exists:students,student_email',
        'content' => 'required|string',
        'publish_at' => 'nullable|date',
    ]);

    $announcement = Announcement::create([
        'title' => $request->title,
        'content' => $request->content,
        'publish_at' => $request->publish_at,
        'is_active' => $request->has('is_active') ? true : false,
    ]);

    foreach ($request->to_emails as $email) {
        Mail::to($email)->queue(new AnnouncementMail($announcement));
    }

    return response()->json([
        'success' => true,
        'message' => 'Announcement created and emails queued successfully.'
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

        foreach ($recipients as $email) {
            Mail::to($email)->queue(new AnnouncementMail($announcement));
        }

        return back()->with('success', 'Announcement sent successfully!');
    }
}