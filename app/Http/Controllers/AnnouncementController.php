<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
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
         $students = Student::select('student_email')->whereNotNull('student_email')->distinct()->get();
         $tutors = Tutor::select('email')->whereNotNull('email')->distinct()->get();
     
         return view('announcements.create', compact('students', 'tutors'));
     }
     

     public function store(Request $request)
     {
         // Validate inputs
         $validated = $request->validate([
             'title' => 'required|string|max:255',
             'to_emails' => 'required|array|min:1',
             'to_emails.*' => 'email|distinct|exists:students,student_email',
             'from_email' => 'required|email|exists:tutors,email',
             'content' => 'required|string',
             'publish_at' => 'nullable|date',
         ]);
     
         // Create the announcement
         $announcement = Announcement::create([
             'title' => $validated['title'],
             'content' => $validated['content'],
             'publish_at' => $validated['publish_at'],
             'is_active' => $request->has('is_active'),
             'from_email' => $validated['from_email'],
         ]);
     
         // Send to selected students
         foreach ($validated['to_emails'] as $email) {
            Mail::to($email)->queue(new AnnouncementMail($announcement, $validated['from_email']));

         }
     
         return response()->json([
             'success' => true,
             'message' => 'Announcement created and emails queued successfully.'
         ]);
     }
     
    /**1
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