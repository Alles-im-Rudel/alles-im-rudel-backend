<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewPostNotification extends Notification
{
    use Queueable;

    protected Post $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Neuer Post')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('es wurde ein neuer Post mit dem Titel "' . $this->post->title . '" erstellt!')
            ->action('Post ansehen', env('APP_FRONTEND_URL') . '/posts/' . $this->post->id)
            ->salutation(new HtmlString('Viele Grüße<br>Timm, Silas & Nick'));
    }
}
