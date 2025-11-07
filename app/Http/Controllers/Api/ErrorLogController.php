<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorLogController extends Controller 
{
    /**
     * Store a new error log
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'error_type' => 'required|string',
                'error_message' => 'required|string',
                'error_stack' => 'nullable|string',
                'user_email' => 'nullable|email',
                'student_name' => 'nullable|string',
                'stripe_payment_id' => 'nullable|string',
                'form_data' => 'nullable|array',
                'browser_info' => 'required|array',
                'severity' => 'required|in:low,medium,high,critical',
            ]);

            // Create error log
            $errorLog = ErrorLog::create($validated);

            // If critical error with payment ID, send immediate alert
            if ($validated['severity'] === 'critical' && !empty($validated['stripe_payment_id'])) {
                $this->sendCriticalErrorAlert($errorLog);
            }

            // Log to Laravel logs as well
            Log::channel('daily')->error('Frontend Error', $validated);

            return response()->json([
                'success' => true,
                'message' => 'Error logged successfully',
                'log_id' => $errorLog->id,
            ], 200);

        } catch (\Exception $e) {
            // If logging fails, at least log the failure
            Log::error('Failed to store error log', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to log error',
            ], 500);
        }
    }

    /**
     * Get all error logs (admin only)
     */
    public function index(Request $request)
    {
        $query = ErrorLog::query();

        // Filter by severity
        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        // Filter by resolved status
        if ($request->has('resolved')) {
            $query->where('resolved', $request->boolean('resolved'));
        }

        // Filter by error type
        if ($request->has('error_type')) {
            $query->where('error_type', $request->error_type);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }

        return response()->json([
            'success' => true,
            'errors' => $query->orderBy('created_at', 'desc')
                             ->paginate(50),
        ]);
    }

    /**
     * Get critical unresolved errors
     */
    public function critical()
    {
        return response()->json([
            'success' => true,
            'errors' => ErrorLog::criticalUnresolved(),
        ]);
    }

    /**
     * Mark error as resolved
     */
    public function resolve(Request $request, $id)
    {
        $errorLog = ErrorLog::findOrFail($id);
        
        $errorLog->markResolved($request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Error marked as resolved',
        ]);
    }

    /**
     * Send email alert for critical errors
     */
    private function sendCriticalErrorAlert($errorLog)
    {
        try {
            Mail::send('emails.critical-error', ['error' => $errorLog], function ($message) use ($errorLog) {
                $message->to('support@zoffness.academy')
                       ->subject('🚨 CRITICAL: Payment Succeeded but Registration Failed')
                       ->priority(1);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send critical error email', [
                'error' => $e->getMessage(),
                'log_id' => $errorLog->id,
            ]);
        }
    }
}
