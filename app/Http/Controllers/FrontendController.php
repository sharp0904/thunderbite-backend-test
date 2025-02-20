<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\View\View;
use App\Models\GameSession;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign, Request $request): View
    {
        $campaignId = $campaign->id;

        $segment = $request->query('segment');
        
        if (!$segment) {
            $segment = 'low';  // Default value if segment is not present in the URL
        }
        
        $gameSession = GameSession::where('campaign_id', $campaignId)->where('has_won', false)->first();
        
        if (!$gameSession) {
            $gameSession = GameSession::create([
                'campaign_id' => $campaignId, // Set the campaign_id
                'tiles' => json_encode([]), // Initialize the tiles as an empty array
                'has_won' => false, // Mark the game as not won
                'segment_type' => $segment, 
            ]);
        }
        $config = [
            'apiPath' => '/api/flip',
            'gameId' => $gameSession->id, // Use the dynamically generated gameId
            'revealedTiles' => [[
                'index' => 0,
                'image' => '/assets/1.png'
            ]],   
        ];

        $jsonConfig = json_encode($config);

        return view('frontend.index', ['config' => $jsonConfig]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
