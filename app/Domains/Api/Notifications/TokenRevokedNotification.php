<?php

namespace App\Domains\Api\Notifications;

use App\Domains\Api\Models\PersonalAccessToken;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class TokenRevokedNotification
 *
 * @package App\Domains\Api\Notifications
 */
class TokenRevokedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Authenticatable $revoker;
    public PersonalAccessToken $personalAccessToken;

    /**
     * TokenRevokedNotification constructor.
     *
     * @param  Authenticatable     $revoker             The user entity from who revoked the token.
     * @param  PersonalAccessToken $personalAccessToken The database entity from the personal access token.
     * @return void
     */
    public function __construct(Authenticatable $revoker, PersonalAccessToken $personalAccessToken)
    {
        $this->revoker = $revoker;
        $this->personalAccessToken = $personalAccessToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return string|array
     */
    public function via(): string
    {
        return 'mail';
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  User $notifiable The user entity from the token owner.
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('Personal access token revoked'))
            ->bcc($this->revoker->email)
            ->greeting(__('Hello :user', ['user' => $notifiable->name]))
            ->line(__('Hereby we inform u that your personal access token for :service is revoked by :user', [
               'user' => $this->revoker->name, 'service' => $this->personalAccessToken->name
            ]))->line(__('If you got any questions feel free to reply to this email.'));
    }
}
