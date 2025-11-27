<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ImportCompletedNotification extends Notification
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
            'type' => 'import_completed',
            'message' => $this->data['message'] ?? 'Import completed',
            'file' => $this->data['file'] ?? null,
            'counts' => $this->data['counts'] ?? 0,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
