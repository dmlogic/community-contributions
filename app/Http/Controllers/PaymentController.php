<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use App\Models\CampaignRequest;
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\OfflinePaymentRequest;
use App\Http\Requests\PaymentWebhookRequest;

class PaymentController extends Controller
{
    public function form(Request $request)
    {
        $formData = [
            'fund' => Fund::findOrFail($request->input('fund_id')),
            'amount' => 50,
            'request' => null,
        ];
        /**
         * Here we attempt to tie the payment form to an existing payment request.
         * But if we don't find one, we don't fail. Instead treat it like an additional
         * funding request and allow the resident to continue to payment
         */
        if ($formData['request'] = CampaignRequest::loadFromHttpRequest($request)) {
            $formData['amount'] = $formData['request']->amount / 100;
        }

        return Inertia::render('Payment/Form', $formData);
    }

    public function offlineForm(Request $request): Response
    {
        $formData = [
            'fund' => Fund::findOrFail($request->input('fund_id')),
            'paymentDate' => now()->format('Y-m-d'),

            // This form requires a valid campaign request record to have any meaning
            'request' => CampaignRequest::where('user_id', '=', $request->user()->id)
                                        ->with('campaign')
                                        ->findOrFail($request->input('request_id')),
        ];

        return Inertia::render('Payment/OfflineForm', $formData);
    }

    public function offline(OfflinePaymentRequest $request): RedirectResponse
    {
        $request->createLedgerEntry();

        return Redirect::route('dashboard')
                       ->with('success', 'Payment advice logged');
    }

    public function checkout(StripeClient $stripe, PaymentRequest $request): \Symfony\Component\HttpFoundation\Response
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

    public function confirm(PaymentWebhookRequest $request): string
    {
        $request->processWebhook();

        return 'ok';
    }

    public function success()
    {
        return Inertia::render('Payment/Success');
    }

    public function error()
    {
        return Inertia::render('Payment/Failure');
    }
}
