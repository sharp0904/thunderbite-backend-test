<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = ['campaign_id', 'tiles', 'has_won', 'prize_id', 'segment_type'];

    protected $casts = [
        'tiles' => 'array'
    ];

    public function awardPrize($prizedId){
        $this->has_won = true;
        $this->prize_id = $prizedId;
        $this->save();
    }
    
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
    
}
