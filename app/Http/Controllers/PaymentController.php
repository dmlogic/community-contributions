<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Stripe\StripeClient;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function form()
    {
        return Inertia::render('Payment/Form', [
            'request_id' => $this->query('request_id'),
            'fund_id' => $this->query('fund_id'),
        ]);
    }

    public function checkout(StripeClient $stripe, PaymentRequest $request): RedirectResponse
    {
        $session = $stripe->checkout->sessions->create([
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.error'),
            'customer_email' => $request->user()->email,
            'metadata' => $request->getMetaData(),
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'gbp',
                    'unit_amount' => (int) $request->validated('amount') * 100,
                    'product' => config('services.stripe.product_id'),
                ],
            ]],
            'mode' => 'payment',
        ]);

        return redirect($session->url);
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
