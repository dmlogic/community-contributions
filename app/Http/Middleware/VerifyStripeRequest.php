<?php

namespace App\Http\Middleware;

use Closure;
use Stripe\WebhookSignature;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyStripeRequest
{
    public function handle($request, Closure $next): Response
    {
        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('services.stripe.webhook_secret'),
                config('services.stripe.webhook_tolerance')
            );
        } catch (SignatureVerificationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }

        return $next($request);
    }
}
