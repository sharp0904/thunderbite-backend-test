<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\View\View;
use App\Models\GameSession;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign): View
    {
        $campaignId = $campaign->id;

        $gameSession = GameSession::where('campaign_id', $campaignId)->where('has_won', false)->first();
        
        if (!$gameSession) {
            $gameSession = GameSession::create([
                'campaign_id' => $campaignId, // Set the campaign_id
                'tiles' => json_encode([]), // Initialize the tiles as an empty array
                'has_won' => false, // Mark the game as not won
            ]);
        }
        $config = [
            'apiPath' => '/api/flip',
            'gameId' => $gameSession->id, // Use the dynamically generated gameId
        ];

        $jsonConfig = json_encode($config);

        return view('frontend.index', ['config' => $jsonConfig]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
