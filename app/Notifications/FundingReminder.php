<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class FundingReminder extends FundingRequest
{
    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage);
        $message->subject(config('app.name').' funding reminder');
        $message->markdown(
            'mail.funding-reminder',
            [
                'campaign' => $this->campaign,
                'request' => $this->request,
                'payment_link' => $this->generatePaymentUrl(),
            ]
        );

        return $message;
    }
}
