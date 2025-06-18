<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Mail\AnnouncementMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Bus;
use Throwable;

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
    try {
        // Validate inputs
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'to_emails' => 'required|array|min:1',
            'to_emails.*' => 'email|distinct|exists:students,student_email',
            'content' => 'required|string',
            'publish_at' => 'nullable|date|after:now',
        ]);

        // Start database transaction
        DB::beginTransaction();

        // Get logged-in user's email
        $fromEmail = Auth::user()->email;

        // Create the announcement
        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'publish_at' => $validated['publish_at'] ?? now(),
            'is_active' => $request->boolean('is_active', false),
            'from_email' => $fromEmail,
            'created_by' => Auth::id(),
        ]);

        // Prepare email jobs for batch processing
        $emailJobs = collect($validated['to_emails'])->map(function ($email) use ($announcement, $fromEmail) {
            return new AnnouncementMail($announcement, $fromEmail, $email);
        })->toArray();

        // Dispatch email jobs in batch
        if (!empty($emailJobs)) {
            Bus::batch($emailJobs)
                ->then(function (Batch $batch) {
                    Log::info('Announcement email batch processed successfully', ['batch_id' => $batch->id]);
                })
                ->catch(function (Batch $batch, Throwable $e) {
                    Log::error('Announcement email batch failed', [
                        'batch_id' => $batch->id,
                        'error' => $e->getMessage()
                    ]);
                })
                ->dispatch();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Announcement created and emails queued successfully.',
            'data' => [
                'announcement_id' => $announcement->id,
                'email_count' => count($emailJobs)
            ]
        ], 201);

    } catch (ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Failed to create announcement', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to create announcement. Please try again.'
        ], 500);
    }
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