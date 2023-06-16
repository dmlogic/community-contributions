<?php

namespace Tests\Feature;

use App\Models\Ledger;
use Tests\FeatureTest;
use Tests\SeedsCampaigns;
use App\Enums\LedgerTypes;
use App\Models\CampaignRequest;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;
use App\Http\Middleware\VerifyStripeRequest;

class PaymentTest extends FeatureTest
{
    use SeedsCampaigns;

    public function setUp(): void
    {
        parent::setUp();
        $this->seedCampaigns();
    }

    public function test_voluntary_payment_form_renders(): void
    {
        $this->actingAs($this->seedData['members'][0])
            ->get(route('payment.form', ['fund_id' => 1]))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
            ->has('fund')
            ->where('request', null)
            );
    }

    public function test_request_payment_form_renders(): void
    {
        $request = CampaignRequest::where('user_id', '=', $this->seedData['members'][1]->id)->first();
        $this->actingAs($this->seedData['members'][1])
            ->get(route('payment.form', ['fund_id' => 1, 'request_id' => $request->id]))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
            ->has('fund')
            ->where('request.id', $request->id)
            );
    }

    public function test_cannot_load_anothers_request(): void
    {
        $request = CampaignRequest::where('user_id', '=', $this->seedData['members'][1]->id)->first();
        $this->actingAs($this->seedData['members'][0])
            ->get(route('payment.form', ['fund_id' => 1, 'request_id' => $request->id]))
            ->assertNotFound();
    }

    public function test_offline_form_renders(): void
    {
        $request = CampaignRequest::where('user_id', '=', $this->seedData['members'][2]->id)->first();
        $this->actingAs($this->seedData['members'][2])
            ->get(route('payment.offline-form', ['fund_id' => 1, 'request_id' => $request->id]))
            ->assertInertia(
                fn (AssertableInertia $page) => $page
            ->has('fund.id')
            ->has('request.id')
            ->has('paymentDate')
            );
    }

    public function test_offline_form_is_processed(): void
    {
        $request = CampaignRequest::where('user_id', '=', $this->seedData['members'][2]->id)->first();
        $this->actingAs($this->seedData['members'][2]);
        $response = $this->post(route('payment.offline'), [
            'payment_date' => now()->format('Y-m-d'),
            'fund_id' => 1,
            'request_id' => $request->id,
        ]);
        $createdLedger = Ledger::latest()->first();

        $this->assertSame($createdLedger->type, LedgerTypes::RESIDENT_OFFLINE->value);
        $this->assertNull($createdLedger->verified_at);
        $this->assertSame($createdLedger->user_id, $this->seedData['members'][2]->id);
        $this->assertNull($createdLedger->verified_at);
        $this->assertDatabaseHas('campaign_requests', ['id' => $request->id, 'ledger_id' => $createdLedger->id]);
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

    public function test_only_one_ledger_per_payment_intent(): void
    {
        // with reference to ledger.provider_reference, ensure we can't double process
        // an entry. This could be via validation or a separate "ok" bailout check
        $this->markTestIncomplete();
    }

    public function test_payment_receipt_is_sent(): void
    {
        // this is the SendOnlinePaymentReceipt notification
        $this->markTestIncomplete();
    }

    // ------------------------------------------------------------------------

    private function submitWebhookPayload($amount, $userId, $requestId): TestResponse
    {
        $payload = file_get_contents(__DIR__ . '/../stripe_responses/checkout_success.json');
        $payload = json_decode(
            str_replace(
                ['{amount}', '{user_id}', '{fund_id}', '{request_id}'],
                [$amount, $userId, $this->seedData['fund']->id, $requestId],
                $payload
            ),
            true
        );

        return $this->postJson(route('payment.confirm'), $payload);
    }
}
