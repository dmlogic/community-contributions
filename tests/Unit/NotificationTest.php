<?php

namespace Tests\Unit;

use App\Models\Fund;
use App\Models\User;
use App\Models\Campaign;
use Tests\CreatesApplication;
use App\Models\CampaignRequest;
use App\Notifications\FundingRequest;
use App\Notifications\FundingReminder;
use Illuminate\Foundation\Testing\TestCase;

class NotificationTest extends TestCase
{
    use CreatesApplication;

    public function test_funding_request_has_expected_data(): void
    {
        $models = $this->mockModels();
        $notification = new FundingRequest($models->request);

        $contents = $notification->toMail(new User)->render();

        $this->assertStringContainsString($models->fund->name, $contents);
        $this->assertStringContainsString($models->campaign->description, $contents);
    }

    public function test_funding_reminder_has_expected_data(): void
    {
        $models = $this->mockModels();
        $notification = new FundingReminder($models->request);
        $contents = $notification->toMail(new User)->render();

        $this->assertStringContainsString($models->campaign->raised_value, $contents);
        $this->assertStringContainsString($models->request->created_at->format('d/m/Y'), $contents);
    }

    private function mockModels(): object
    {
        $fund = Fund::factory()->make();
        $campaign = Campaign::factory()->make();
        $campaign->setRelation('fund', $fund);
        $request = new CampaignRequest(['amount' => 100, 'created_at' => now()]);
        $request->setRelation('campaign', $campaign);

        return (object) [
            'fund' => $fund,
            'campaign' => $campaign,
            'request' => $request,
        ];
    }
}
