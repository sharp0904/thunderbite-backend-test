<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\GameSession;
use Illuminate\Database\Seeder;
use DB;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        GameSession::truncate();

        Campaign::truncate();

        Campaign::create([
            'timezone' => 'Europe/London',
            'name' => 'Test Campaign 1',
            'slug' => 'test-campaign-1',
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addDays(7)->endOfDay(),
        ]);

        Campaign::create([
            'timezone' => 'Europe/London',
            'name' => 'Test Campaign 2',
            'slug' => 'test-campaign-2',
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addDays(7)->endOfDay(),
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
