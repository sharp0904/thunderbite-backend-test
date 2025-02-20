<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\GameSession;
use App\Models\Prize;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function flip(Request $request)
    {
        /**
         * This is a simplified example to demonstrate interaction with the provided frontend (FE).
         * The game objective is to collect three matching tiles to win a prize. Once three matching tiles are collected:
         *   - The game ends.
         *   - The prize is awarded, and its daily volume limit (defined in the back office) must be updated.
         *
         * Requirements:
         * - Use the database layer to store and manage all game-related data, including game state and prize counts.
         * - Cache is used here only for demonstration purposes and should be replaced with proper database storage.
         */

        // Simulate the current move count using cache (replace with database in production).
        $gameSession = GameSession::find($request->gameId);
        $segment_type = $gameSession->segment_type;
        $campaign_id = $gameSession->campaign_id;

        $tile_index = $request->tileIndex;

        
        // If no game session is found or it's already won, return an error
        if (!$gameSession || $gameSession->has_won) {
            return response()->json([
                'message' => 'Game session not found or already completed.',
            ], 400);
        }

        $tiles = json_decode($gameSession->tiles, true);

        $prize = Prize::selectTile($campaign_id, $segment_type);

        $tiles[] = [
            'index' => $tile_index,
            'image' => $prize->image
        ];
        $gameSession->tiles = json_encode($tiles);
        $gameSession->save();

        $tileCounts = array_count_values(array_column($tiles, 'image'));
        $matchingTiles = array_filter($tileCounts, fn($count) => $count >= 3);

        if (count($matchingTiles) > 0) {
            // Check if the prize is still available
            if ($prize && $prize->isAvailableForToday()) {
                // Award the prize and update the session
                $gameSession->awardPrize($prize->id);
                return response()->json([
                    'tileImage' => asset("storage/{$prize->image}"),
                    'message' => 'You won! Prize: ' . $prize->name,
                    'prize' => $prize->name,
                    'daily_limit' => $prize->daily_limit,
                    'prize_image' => asset("storage/{$prize->image}"),
                ]);
            } else {
                return response()->json([
                    'tileImage' => asset("storage/{$prize->image}"),
                    'message' => 'The prize is out of stock for today.',
                ]);
            }
        }

        // If there is no match yet, continue the game
        return response()->json([
            'tileImage' => asset("storage/{$prize->image}"),
        ]);
    }
}
