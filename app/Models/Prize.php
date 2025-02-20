<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'segment',
        'weight',
        'starts_at',
        'ends_at',
        'daily_limit',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function selectTile(int $campaign_id, string $segment = null)
    {   
        return self::where('campaign_id', $campaign_id) // Filter by campaign_id
        ->where('segment', $segment)
        ->where('daily_limit', '>', 0) // Ensure the prize is available today
        ->orderByRaw('-LOG(1.0 - RAND()) / weight')
        ->first();
    }

    public function isAvailableForToday(): bool
    {
        $today = now()->toDateString();
        $prizeWonTodayCount = GameSession::where('prize_id', $this->id)
                            ->whereDate('created_at', $today)
                            ->count();
        return $prizeWonTodayCount < $this->daily_limit;
    }
}
