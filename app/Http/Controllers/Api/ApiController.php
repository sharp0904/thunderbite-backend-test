<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\GameSession;
use App\Models\Prize;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function flip()
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
        $gameSession = GameSession::firstOrCreate(
            ['user_id' => $request->user()->id, 'has_won' => false],
            ['titles' => json_encode([])]
        )

        $tiles = $gameSession->tiles;
        $tile = random_int(1, 7);

        $tiles[] = $tile;
        $gameSession->tiles = $ti;
        $gameSession->save();

        $tileCounts = array_count_values($tiles);
        $matchingTiles = array_filter($tileCounts, fn($count) => $count >= 3);

        if(count($matchingTiles) > 0){
            $prize = Prize::first();

            if($prize->daily_limit > 0) {
                $gameSession->awardPrize($prize->id);
                return response()->json([
                    'tileImage' => asset("assets/{$tile}.png"),
                    'message' => 'You won! Prize:'.$prize->name,
                    'prize' => $prize->name,
                    'daily_limit' => $prize->daily_limit,
                ]);
            } else {
                return response()->json([
                    'tileImage' => asset("assets/{$tile}.png"),
                    'message' => 'The prize is out of stock for today',
                ]);
            }
        }

        return response()->json([
            'tileImage' => asset("assets/{$tile}.png"),
            'message' => 'No match yet, keep playing!',
        ])
    }
}
