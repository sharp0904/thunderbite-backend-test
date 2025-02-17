<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Prize;
use Illuminate\Database\Seeder;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prize::truncate();

        $campaigns = Campaign::all();

        foreach ($campaigns as $campaign) {
            Prize::insert([
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 1',
                    'segment' => 'low',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 2',
                    'segment' => 'low',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 3',
                    'segment' => 'low',
                    'weight' => '50.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 1',
                    'segment' => 'med',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 2',
                    'segment' => 'med',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 3',
                    'segment' => 'med',
                    'weight' => '50.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 1',
                    'segment' => 'high',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 2',
                    'segment' => 'high',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 3',
                    'segment' => 'high',
                    'weight' => '50.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
            ]);
        }
    }
}
