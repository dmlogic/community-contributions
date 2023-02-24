<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\WebhookRequest;
use App\Models\CampaignRequest;
use App\Models\Fund;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function form(Request $request)
    {
        $formData = [
            'fund' => Fund::findOrFail($request->input('fund_id')),
            'amount' => 50,
            'request' => null
        ];
        if($request->input('request_id')) {
            $formData['request'] = CampaignRequest::where('user_id',$request->user()->id)
                                        ->with('campaign')
                                        ->findOrFail($request->input('request_id'));
            $formData['amount'] =  $formData['request']->amount / 100;
        }
        return Inertia::render('Payment/Form',$formData);
    }

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
        return Inertia::render('Payment/Success');
    }

    public function error()
    {
        return Inertia::render('Payment/Failure');
    }
}
