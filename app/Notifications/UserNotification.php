<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $status;
    public string $message;
    public bool $is_blog;
    public string $title;
    public function __construct(string $message, string $status, bool $is_blog = false,$title = '')
    {
        $this->message = $message;
        $this->status = $status;
        $this->is_blog = $is_blog;
        $this->title = $title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Store notification in the database
    }

    /**
     * Get the array representation of the notification for storing in the database.
     */
    public function toArray(object $notifiable): array
    {
        $notification = [
            'message'=> $this->message,
            'status' => $this->status,
        ];
        if ($this->is_blog){
            $notification['title'] = $this->title;
        }
        return $notification;
    }

//    public function toBroadcast(object $notifiable): BroadcastMessage
//    {
//        return new BroadcastMessage([
//            'message'=> $this->message,
//            'status' => 'new_user',
//            'created_at' => $this->user->created_at,
//        ]);
//    }
}
