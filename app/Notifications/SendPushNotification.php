<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPushNotification extends Notification
{
    use Queueable;

    public $title;
    public $description;
    public $image;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $description, $image = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject($this->title)
            ->line($this->description);

        if ($this->image) {
            $mailMessage->line('Check out the attached image.')
                ->action('View Notification', url('/storage/' . $this->image))
                ->line('Thank you for using our application!');
        }

        return $mailMessage;
    }


    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
        ];
    }
}
