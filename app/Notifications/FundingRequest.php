<?php

namespace App\Notifications;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use App\Models\CampaignRequest;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FundingRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected CampaignRequest $request;

    protected Campaign $campaign;

    public function __construct(CampaignRequest $request)
    {
        $this->request = $request;
        $this->campaign = $request->campaign;
    }

    public function generatePaymentUrl()
    {
        return '@todo';
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage);
        $message->subject(config('app.name').' funding request');
        $message->markdown(
            'mail.funding-request',
            [
                'campaign' => $this->campaign,
                'request' => $this->request,
                'payment_link' => $this->generatePaymentUrl(),
            ]
        );

        return $message;
    }
}
