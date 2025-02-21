<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\View\View;
use App\Models\Game;
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
        $account = $request->query('a');
        
        if (!$segment) {
            $segment = 'low';  // Default value if segment is not present in the URL
        }

        $game = Game::createOrFindGame($campaignId, $segment, $account);

        $config = [
            'apiPath' => '/api/flip',
            'gameId' => $game->id, // Use the dynamically generated gameId
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
