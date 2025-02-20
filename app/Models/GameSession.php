<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = ['campaign_id', 'tiles', 'has_won', 'prize_id'];

    protected $casts = [
        'tiles' => 'array'
    ];

    public function awardPrize($prizedId){
        $this->has_won = true;
        $this->prize_id = $prizedId;

        $prize = Prize::find($prizedId);
        if($prize) {
            $prize->daily_limit -= 1;
            $prize->save();
        }
        $this->save();
    }
    
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
    
}
