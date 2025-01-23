<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameResultController extends Controller
{
    public function showResult(Request $request)
    {
        $game = session('game');
        
        if (!isset($game['winner'])) {
            return redirect()->route('poker')->with('error', 'ゲーム結果が見つかりません');
        }

        $winner = $game['winner'];
        $winningHand = $game['winning_hand'];
        
        $resultData = [
            'winner' => [
                'id' => substr($winner, -1),
                'money' => $game['players'][$winner]['money'],
                'hand_type' => $winningHand['type'],
                'winning_amount' => $game['pot']
            ],
            'players' => $game['players'],
            'community_cards' => $game['dealer']['card']
        ];

        return view('poker.result', ['game' => $resultData]);
    }

    public function startNewGame(Request $request)
    {
        $request->session()->forget('game');
        return redirect()->route('poker.start');
    }
}