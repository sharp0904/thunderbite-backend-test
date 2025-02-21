<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Game;
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
        $game = Game::find($request->gameId);
        $segment_type = $game->segment_type;
        $campaign_id = $game->campaign_id;
        $tile_index = $request->tileIndex;
        // If no game session is found or it's already won, return an error
        if (!$game || $game->has_won || $game->has_lost) {
            return response()->json([
                'message' => 'Game not found or already completed.',
            ], 400);
        }
        $tiles = json_decode($game->tiles, true);
        $existingTile = collect($tiles)->firstWhere('index', $tile_index);
        if ($existingTile) {
            // If tile already exists, return its image
            return response()->json([
                'tileImage' => asset("storage/{$existingTile['image']}"),
            ]);
        }
        $prize = Prize::selectTile($campaign_id, $segment_type);
        $tiles[] = [
            'index' => $tile_index,
            'image' => $prize->image
        ];
        if (count($tiles) >= 25) {
            // Mark the game as lost and update the session
            $game->has_lost = true; // Set the has_lost flag to true
            $game->revealed_at = now();
            $game->save();
            return response()->json([
                'message' => 'Game over! You lost the game. You reached the maximum number of tiles.',
            ], 400);
        }
        $game->tiles = json_encode($tiles);
        $game->revealed_at = now();
        $game->save();
        $tileCounts = array_count_values(array_column($tiles, 'image'));
        $matchingTiles = array_filter($tileCounts, fn($count) => $count >= 3);
        if (count($matchingTiles) > 0) {
            // Check if the prize is still available
            if ($prize && $prize->isAvailableForToday()) {
                // Award the prize and update the session
                $game->prize_id = $prize->id;
                $game->has_won = true;
                $game->save();
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
