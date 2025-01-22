<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function showPlayerInfo($name)
    {
        $player = $this-player->where('name', $name)->first();

        if (!$player){
            return response('Player not found', 404);
        }

        return view('poker', [
            'name' => $player->name,
            'balance' => $player->balance
        ]);
    }

}