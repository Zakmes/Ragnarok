<?php

namespace App\Domains\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class UserCreatedNotification
 *
 * @package App\Domains\Users\Notifications
 */
class UserCreatedNotification extends Notification
{
    use Queueable;

    /**
     * The system generated password fro the user account.
     *
     * @var string
     */
    public string $password;

    /**
     * Create a new notification instance.
     *
     * @param  string $password The system generated password.
     * @return void
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('There is created an login for u on ' . config('app.name'))
            ->greeting('Hello,')
            ->line('A administrator has created an login for u on ' . config('app.name'))
            ->line('You can login with the following password: ' . $this->password)
            ->action('login', route('login'));
    }
}
