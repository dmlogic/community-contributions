<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InviteMember extends Notification
{
    use Queueable;

    protected Invitation $invite;

    public function __construct(Invitation $invite)
    {
        $this->invite = $invite;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage);
        $message->subject(config('app.name').' invitation');
        $message->replyTo(config('mail.from.address'), config('mail.from.name'));
        $message->markdown(
            'mail.invite-user',
            [
                'invite' => $this->invite,
            ]
        );

        return $message;
    }
}
