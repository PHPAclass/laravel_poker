<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'balance', // 所持金
    ];

    public function hands()
    {
        return $this->hasMany(Hand::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}
