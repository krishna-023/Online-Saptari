<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ImportCompleteNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $email) {}

    public function handle()
    {
        Mail::raw('Your item import is complete!', function($message) {
            $message->to($this->email)->subject('Import Complete');
        });
    }
}

