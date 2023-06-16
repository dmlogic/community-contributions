<?php

namespace App\Notifications;

use App\Models\Ledger;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OnlinePaymentReceipt extends Notification
{
    use Queueable;

    public Ledger $ledger;

    public function __construct(Ledger $ledger)
    {
        $this->ledger = $ledger;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage);
        $message->subject(config('app.name') . ' payment received');
        $message->markdown(
            'mail.online-payment-receipt',
            [
                'ledger' => $this->ledger,
            ]
        );

        return $message;
    }
}
