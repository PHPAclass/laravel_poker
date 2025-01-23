<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'suit', // 図柄
        'rank', // 数字
    ];

}
