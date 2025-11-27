<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ImportFailedNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'import_failed',
            'message' => $this->data['message'] ?? 'Import failed',
            'file' => $this->data['file'] ?? null,
            'error' => $this->data['error'] ?? null,
            'counts' => $this->data['counts'] ?? 0,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
