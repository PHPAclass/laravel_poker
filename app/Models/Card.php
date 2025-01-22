<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'suit', // 図柄
        'rank', // 数字
        'image',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
