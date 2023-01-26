<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Stripe\StripeClient;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\WebhookRequest;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function checkout(StripeClient $stripe, PaymentRequest $request): Response
    {
        $session = $stripe->checkout->sessions->create([
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.error'),
            'customer_email' => $request->user()->email,
            'metadata' => $request->getMetaData(),
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'GBP',
                    'unit_amount' => (int) $request->validated('amount') * 100,
                    'product' => config('services.stripe.product_id'),
                ],
            ]],
            'mode' => 'payment',
        ]);

        return Inertia::location($session->url);
    }

    public function confirm(WebhookRequest $request)
    {
        $request->processWebhook();

        return 'ok';
    }

    public function success()
    {
        return '@todo payment success';
    }

    public function error()
    {
        return '@todo payment error';
    }
}
