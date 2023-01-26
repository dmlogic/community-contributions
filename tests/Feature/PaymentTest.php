<?php

namespace Tests\Feature;

use App\Models\Ledger;
use Tests\FeatureTest;
use Tests\SeedsCampaigns;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;
use App\Http\Middleware\VerifyStripeRequest;

class PaymentTest extends FeatureTest
{
    use SeedsCampaigns;

    public function setUp(): void
    {
        parent::setUp();
        $this->seedCampaigns();
    }

    public function test_checkout_fails_without_expected_data(): void
    {
        $resident = $this->seedData['members'][0];
        $this->actingAs($resident);
        $this->post(route('payment.checkout'), [])
        ->assertInvalid(['amount', 'fund_id']);
    }

    public function test_checkout_session_is_created_and_redirects_to_stripe(): void
    {
        $resident = $this->seedData['members'][0];
        $request = CampaignRequest::where('user_id', $resident->id)->first();

        $this->actingAs($resident);
        $this->post(route('payment.checkout'), [
            'amount' => 100,
            'request_id' => $request->id,
            'fund_id' => $this->seedData['fund']->id,
        ])
        ->assertRedirectContains('https://checkout.stripe.com/c/pay/');
    }

    public function test_webhook_fails_with_invalid_signature(): void
    {
        $resident = $this->seedData['members'][0];
        $request = CampaignRequest::where('user_id', $resident->id)->first();
        $this->submitWebhookPayload(1234, $resident->id, $request->id)
            ->assertForbidden();
    }

    public function test_webhook_creates_ledger_entry_and_updates_relationships(): void
    {
        $resident = $this->seedData['members'][0];
        $request = CampaignRequest::where('user_id', $resident->id)->first();
        $this->withoutMiddleware(VerifyStripeRequest::class)
             ->submitWebhookPayload(1234, $resident->id, $request->id)
             ->assertOk();

        // ledger created and verified
        $ledger = Ledger::latest()->first();
        $this->assertEquals($ledger->fund_id, $this->seedData['fund']->id);
        $this->assertEquals($ledger->user_id, $resident->id);
        $this->assertEquals($ledger->type, LedgerTypes::RESIDENT_REQUEST->value);
        $this->assertNotNull($ledger->verified_at);

        // fund updated
        $this->assertDatabaseHas('funds', ['id' => $this->seedData['fund']->id, 'balance' => $this->seedData['fund']->balance + 1234]);
        // request updated
        $this->assertDatabaseHas('campaign_requests', ['id' => $request->id, 'ledger_id' => $ledger->id]);
    }

    public function test_sundry_endpoints_render(): void
    {
        $this->actingAs($this->adminUser())
             ->get(route('payment.success'))
             ->assertOk();
        $this->get(route('payment.error'))
             ->assertOk();
    }

    // ------------------------------------------------------------------------

    private function submitWebhookPayload($amount, $userId, $requestId)
    {
        $payload = file_get_contents(__DIR__.'/../stripe_responses/checkout_success.json');
        $payload = json_decode(
            str_replace(
                ['{amount}', '{user_id}', '{fund_id}', '{request_id}'],
                [$amount, $userId, $this->seedData['fund']->id, $requestId],
                $payload
            ), true);

        return $this->postJson(route('payment.confirm'), $payload);
    }
}
