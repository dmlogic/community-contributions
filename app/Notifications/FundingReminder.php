<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class FundingReminder extends FundingRequest
{
    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage);
        $message->subject(config('app.name').' funding reminder');
        $message->replyTo(config('mail.from.address'), config('mail.from.name'));
        $message->markdown(
            'mail.funding-reminder',
            [
                'campaign' => $this->campaign,
                'request' => $this->request,
                'payment_link' => $this->generatePaymentUrl(),
                'offline_link' => $this->generatePaymentUrl('payment.offline'),
            ]
        );

        return $message;
    }
}
