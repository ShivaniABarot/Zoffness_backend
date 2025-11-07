<?php

namespace App\Console\Commands;

use App\Models\ErrorLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckUnresolvedErrors extends Command
{
    protected $signature = 'errors:check-unresolved';
    protected $description = 'Check for unresolved critical errors';

    public function handle()
    {
        $criticalErrors = ErrorLog::criticalUnresolved();

        if ($criticalErrors->count() > 0) {
            $this->error("Found {$criticalErrors->count()} unresolved critical errors!");
            
            foreach ($criticalErrors as $error) {
                $this->line("- [{$error->created_at}] {$error->error_type}: {$error->error_message}");
                if ($error->stripe_payment_id) {
                    $this->line("  Payment ID: {$error->stripe_payment_id}");
                }
            }

            // Send summary email
            // Mail::send(...);
        } else {
            $this->info('No unresolved critical errors. All good!');
        }

        return 0;
    }
}
