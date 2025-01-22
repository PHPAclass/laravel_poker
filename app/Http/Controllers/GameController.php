<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers;

class GameController extends Controller
{
    protected $scoreService;

    public function __construct(ScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    public function startGame() {
        $deck = $this->createDeck();  // デッキを作成
        shuffle($deck);  // カードをシャッフルシャッフる社ffるプルるんプルルーん
        // プレイヤーにカードを配る
        $players = [
            'player1' => array_splice($deck, 0, 5),  // 最初の5枚
            'player2' => array_aplice($deck, 0, 5),  // 次の5舞
            'player3' => array_aplice($deck, 0, 5),  // 次
            'player4' => array_aplice($deck, 0, 5),  // t
        ];

        $remainDeck = count($deck);

        return response()->json([
            'players' => $players,
            'remainingDeck' => $remainDeck,
        ]);
    }

    public function updateScore(Request $request, $gameId, $playerId)
    {
        $cards = $request->input('cards');

        $score = $this->scoreService->CalculateScore($gameId, $playerId, $cards);

        return response()->json(['score' => $score], 200);
    }

    
}