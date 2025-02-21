<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'prize_id', 'account', 'revealed_at', 'segment_type', 'has_won', 'has_lost', 'tiles'];

    protected function casts(): array
    {
        return [
            'revealed_at' => 'datetime',
        ];
    }

    public static function filter(?string $account = null, ?int $prizeId = null, ?string $fromDate = null, ?string $tillDate = null)
    {
        $query = self::query();
        $campaign = Campaign::find(session('activeCampaign'));

        // When filtering by dates, keep in mind `revealed_at` should be stored in Campaign timezone

        return $query;
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }
    
    public function scopeFindUnfinishedGame($query, $campaignId, $segmentType)
    {
        return $query->where('campaign_id', $campaignId)
            ->where('segment_type', $segmentType)
            ->where('has_won', false)
            ->where('has_lost', false);
    }
    
    public static function createOrFindGame(int $campaignId, string $segment, string $account)
    {
        // Check if an active game exists for the given campaign and segment
        $existingGame = self::findUnfinishedGame($campaignId, $segment)->first();
        if ($existingGame) {
            // Optionally, return the existing game if an active game already exists
            return $existingGame;
        }
        if (is_numeric($account)) {
            $accountName = User::findNameByID($account);
            // If no name is found, use the account directly
            if ($accountName === null) {
                $accountName = $account;
            }
        } else {
            // If account is not numeric, use it directly (this is for case where account is a string)
            $accountName = $account;
        }
        // Otherwise, create a new game session
        $game = self::create([
            'campaign_id'   => $campaignId,
            'segment_type'  => $segment,
            'account'       => $accountName,
            'revealed_at'   => now(),
            'has_won'       => false,
            'has_lost'      => false,
            'tiles'         => json_encode([]),  // Initialize with empty tiles
        ]);
        return $game;
    }
}
