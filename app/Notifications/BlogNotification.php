<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Blog;
use App\Models\User;

class BlogNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Blog $blog;
    protected string $status;

    protected string $message;


    /**
     * Create a new notification instance.
     */
    public function __construct(Blog $blog,string $message, string $status)
    {
        $this->blog = $blog;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast']; // Store notification in the database
    }

    /**
     * Get the array representation of the notification for storing in the database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'blog_id' => $this->blog->id,
            'author'   => $this->blog->user->name,
            'message'  => $this->message,
            'thumbnail' => $this->blog->image,
            'title' => $this->blog->title,
            'status' => $this->status,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'blog_id' => $this->blog->id,
            'author'   => $this->blog->user->name,
            'message'  => $this->message,
            'thumbnail' => url($this->blog->image),
            'title' => $this->blog->title,
            'status' => $this->status,
            'created_at' => $this->blog->created_at,
        ]);
    }
}
