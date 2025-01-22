<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hand extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'game_id',
        'card_id',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function game()
    {
        return $this->belongsto(Game::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
