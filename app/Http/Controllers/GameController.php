<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    // 初期チップ額
    private const INITIAL_CHIPS = 1000;
    
    // 最小ベット額
    private const MIN_BET = 10;

    public function startGame(Request $request)
    {
        $request->session()->flush();

        // デッキをデータベースから作成してシャッフル
        $deck = $this->createDeck();
        shuffle($deck);

        // プレイヤーにカードを配る
        $players = [];
        $currentCard = 0;

        for ($i = 1; $i <= 4; $i++) {
            $players['player'.$i] = [
                'card' => [
                    $deck[$currentCard],
                    $deck[$currentCard + 1]
                ],
                'money' => self::INITIAL_CHIPS,
                'bet' => 0,
                'status' => 'active',
                'first' => ($i === 1)
            ];
            $currentCard += 2;
        }

        // ディーラーのカード（コミュニティカード）を設定
        $dealer = [
            'card' => [
                $deck[$currentCard],     // flop 1
                $deck[$currentCard + 1], // flop 2
                $deck[$currentCard + 2], // flop 3
                $deck[$currentCard + 3], // turn
                $deck[$currentCard + 4]  // river
            ],
            'revealed_cards' => 0,
            'current_player' => 'player1',
            'round' => 'pre-flop',
            'highest_bet' => 0,
        ];

        $gameState = [
            'players' => $players,
            'dealer' => $dealer,
            'pot' => 0,
        ];

        $request->session()->put('game', $gameState);
        return redirect()->route('poker');
    }

    public function call(Request $request)
    {
        $game = session('game');
        $currentPlayer = $game['dealer']['current_player'];
        $callAmount = $game['dealer']['highest_bet'] - $game['players'][$currentPlayer]['bet'];

        // プレイヤーの所持金をチェック
        if ($game['players'][$currentPlayer]['money'] < $callAmount) {
            return redirect()->route('poker')->with('error', '所持金が不足しています');
        }

        // ベットを更新
        $game['players'][$currentPlayer]['money'] -= $callAmount;
        $game['players'][$currentPlayer]['bet'] = $game['dealer']['highest_bet'];
        $game['pot'] += $callAmount;

        // 次のプレイヤーに移動
        $game = $this->moveToNextPlayer($game);

        // ラウンドが終了したかチェック
        if ($this->isRoundComplete($game)) {
            $game = $this->moveToNextRound($game);
            
            // river ラウンド終了後の処理
            if ($game['dealer']['round'] === 'game_over') {
                $request->session()->put('game', $game);
                return redirect()->route('poker.result');
            }
        }

        $request->session()->put('game', $game);
        return redirect()->route('poker');
    }

    public function raise(Request $request)
    {
        $game = session('game');
        $currentPlayer = $game['dealer']['current_player'];
        $raiseAmount = $request->input('amount');

        // バリデーション
        if ($raiseAmount < $game['dealer']['highest_bet'] * 2) {
            return redirect()->route('poker')->with('error', '最低でも現在のベットの2倍以上をレイズする必要があります');
        }

        if ($raiseAmount < self::MIN_BET) {
            return redirect()->route('poker')->with('error', '最小ベット額は'.self::MIN_BET.'です');
        }

        // プレイヤーの所持金をチェック
        $totalBet = $raiseAmount - $game['players'][$currentPlayer]['bet'];
        if ($game['players'][$currentPlayer]['money'] < $totalBet) {
            return redirect()->route('poker')->with('error', '所持金が不足しています');
        }

        // ベットを更新
        $game['players'][$currentPlayer]['money'] -= $totalBet;
        $game['players'][$currentPlayer]['bet'] = $raiseAmount;
        $game['dealer']['highest_bet'] = $raiseAmount;
        $game['pot'] += $totalBet;

        // 他のプレイヤーのステータスをリセット
        foreach ($game['players'] as $playerKey => &$player) {
            if ($playerKey !== $currentPlayer && $player['status'] !== 'fold') {
                $player['status'] = 'active';
            }
        }

        // 次のプレイヤーに移動
        $game = $this->moveToNextPlayer($game);

        $request->session()->put('game', $game);
        return redirect()->route('poker');
    }

    public function fold(Request $request)
    {
        $game = session('game');
        $currentPlayer = $game['dealer']['current_player'];

        // プレイヤーをフォールド状態に
        $game['players'][$currentPlayer]['status'] = 'fold';

        // 残りのアクティブプレイヤーをチェック
        $activePlayers = $this->getActivePlayers($game);
        if (count($activePlayers) === 1) {
            // 最後の1人が勝者
            $winner = reset($activePlayers);
            $game['players'][$winner]['money'] += $game['pot'];
            $game['pot'] = 0;
            $game['winner'] = $winner;
            $game['dealer']['round'] = 'game_over';
            
            $request->session()->put('game', $game);
            return redirect()->route('poker')->with('message', 'ゲーム終了！ 勝者: Player ' . substr($winner, -1));
        }

        // 次のプレイヤーに移動
        $game = $this->moveToNextPlayer($game);

        // ラウンドが終了したかチェック
        if ($this->isRoundComplete($game)) {
            $game = $this->moveToNextRound($game);
        }

        $request->session()->put('game', $game);
        return redirect()->route('poker');
    }

    public function check(Request $request)
    {
        $game = session('game');
        $currentPlayer = $game['dealer']['current_player'];

        // チェックが可能か確認
        if ($game['players'][$currentPlayer]['bet'] !== $game['dealer']['highest_bet']) {
            return redirect()->route('poker')->with('error', 'チェックはできません。コールまたはレイズしてください。');
        }

        // 次のプレイヤーに移動
        $game = $this->moveToNextPlayer($game);

        // ラウンドが終了したかチェック
        if ($this->isRoundComplete($game)) {
            $game = $this->moveToNextRound($game);
        }

        $request->session()->put('game', $game);
        return redirect()->route('poker');
    }

    private function createDeck()
    {
        return DB::table('cards')
            ->where('suit', '!=', 'back')
            ->get()
            ->map(function($card) {
                return [
                    'suit' => $card->suit,
                    'rank' => $card->rank
                ];
            })
            ->toArray();
    }

    private function moveToNextPlayer($game)
    {
        $currentPlayerIndex = (int) substr($game['dealer']['current_player'], -1);
        
        do {
            $currentPlayerIndex = ($currentPlayerIndex % 4) + 1;
            $nextPlayer = 'player' . $currentPlayerIndex;
        } while ($game['players'][$nextPlayer]['status'] === 'fold');

        $game['dealer']['current_player'] = $nextPlayer;
        return $game;
    }

    private function isRoundComplete($game)
    {
        $activePlayers = $this->getActivePlayers($game);
        
        foreach ($activePlayers as $playerKey) {
            if ($game['players'][$playerKey]['bet'] !== $game['dealer']['highest_bet']) {
                return false;
            }
        }
        
        return true;
    }

    private function moveToNextRound($game)
    {
        switch ($game['dealer']['round']) {
            case 'pre-flop':
                $game['dealer']['round'] = 'flop';
                $game['dealer']['revealed_cards'] = 3;
                break;
            case 'flop':
                $game['dealer']['round'] = 'turn';
                $game['dealer']['revealed_cards'] = 4;
                break;
            case 'turn':
                $game['dealer']['round'] = 'river';
                $game['dealer']['revealed_cards'] = 5;
                break;
            case 'river':
                $game = $this->evaluateWinner($game);
                break;
        }

        // ベットをリセット
        foreach ($game['players'] as &$player) {
            if ($player['status'] !== 'fold') {
                $player['bet'] = 0;
            }
        }
        $game['dealer']['highest_bet'] = 0;

        return $game;
    }

    private function getActivePlayers($game)
    {
        return array_keys(array_filter($game['players'], function($player) {
            return $player['status'] === 'active';
        }));
    }

    private function evaluateWinner($game)
    {
        $activePlayers = $this->getActivePlayers($game);
        $communityCards = $game['dealer']['card'];
        $playerHands = [];
        
        // 各プレイヤーの最強の手を評価
        foreach ($activePlayers as $playerKey) {
            $playerCards = array_merge($game['players'][$playerKey]['card'], $communityCards);
            $playerHands[$playerKey] = $this->evaluateHand($playerCards);
        }
        
        // 最強の手を持つプレイヤーを決定
        $winner = $this->determineWinner($playerHands);
        
        // 賞金を支払う
        $game['players'][$winner]['money'] += $game['pot'];
        $game['pot'] = 0;
        
        // ゲーム状態を更新
        $game['dealer']['round'] = 'game_over';
        $game['winner'] = $winner;
        $game['winning_hand'] = $playerHands[$winner];
        
        // redirectではなく、更新されたゲーム状態を返す
        return $game;
    }

    private function evaluateHand($cards)
    {
        // 7枚のカードから最強の5枚を見つける
        $allCombinations = $this->getAllFiveCardCombinations($cards);
        $bestHand = ['rank' => 0, 'cards' => [], 'type' => ''];
        
        foreach ($allCombinations as $hand) {
            $evaluation = $this->rankHand($hand);
            if ($evaluation['rank'] > $bestHand['rank']) {
                $bestHand = $evaluation;
            }
        }
        
        return $bestHand;
    }

    private function getAllFiveCardCombinations($cards)
    {
        $combinations = [];
        $count = count($cards);
        
        for ($i = 0; $i < $count - 4; $i++) {
            for ($j = $i + 1; $j < $count - 3; $j++) {
                for ($k = $j + 1; $k < $count - 2; $k++) {
                    for ($l = $k + 1; $l < $count - 1; $l++) {
                        for ($m = $l + 1; $m < $count; $m++) {
                            $combinations[] = [
                                $cards[$i],
                                $cards[$j],
                                $cards[$k],
                                $cards[$l],
                                $cards[$m]
                            ];
                        }
                    }
                }
            }
        }
        
        return $combinations;
    }

    private function rankHand($hand)
    {
        $ranks = array_column($hand, 'rank');
        $suits = array_column($hand, 'suit');
        
        // ランクを数値に変換
        $ranks = array_map(function($rank) {
            switch ($rank) {
                case 'A': return 14;
                case 'K': return 13;
                case 'Q': return 12;
                case 'J': return 11;
                default: return intval($rank);
            }
        }, $ranks);
        sort($ranks);
        
        // 役の判定
        if ($this->isRoyalFlush($ranks, $suits)) {
            return ['rank' => 10, 'cards' => $hand, 'type' => 'ロイヤルストレートフラッシュ'];
        }
        if ($this->isStraightFlush($ranks, $suits)) {
            return ['rank' => 9, 'cards' => $hand, 'type' => 'ストレートフラッシュ'];
        }
        if ($this->isFourOfAKind($ranks)) {
            return ['rank' => 8, 'cards' => $hand, 'type' => 'フォーカード'];
        }
        if ($this->isFullHouse($ranks)) {
            return ['rank' => 7, 'cards' => $hand, 'type' => 'フルハウス'];
        }
        if ($this->isFlush($suits)) {
            return ['rank' => 6, 'cards' => $hand, 'type' => 'フラッシュ'];
        }
        if ($this->isStraight($ranks)) {
            return ['rank' => 5, 'cards' => $hand, 'type' => 'ストレート'];
        }
        if ($this->isThreeOfAKind($ranks)) {
            return ['rank' => 4, 'cards' => $hand, 'type' => 'スリーカード'];
        }
        if ($this->isTwoPair($ranks)) {
            return ['rank' => 3, 'cards' => $hand, 'type' => 'ツーペア'];
        }
        if ($this->isOnePair($ranks)) {
            return ['rank' => 2, 'cards' => $hand, 'type' => 'ワンペア'];
        }
        
        return ['rank' => 1, 'cards' => $hand, 'type' => 'ハイカード'];
    }

    private function isRoyalFlush($ranks, $suits)
    {
        return $this->isStraightFlush($ranks, $suits) && max($ranks) === 14;
    }

    private function isStraightFlush($ranks, $suits)
    {
        return $this->isFlush($suits) && $this->isStraight($ranks);
    }

    private function isFourOfAKind($ranks)
    {
        $counts = array_count_values($ranks);
        return in_array(4, $counts);
    }

    private function isFullHouse($ranks)
    {
        $counts = array_count_values($ranks);
        return in_array(3, $counts) && in_array(2, $counts);
    }

    private function isFlush($suits)
    {
        return count(array_unique($suits)) === 1;
    }

    private function isStraight($ranks)
    {
        // エースを1として扱う特殊ケース（A-2-3-4-5）
        if ($ranks === [2, 3, 4, 5, 14]) {
            return true;
        }
        
        for ($i = 0; $i < 4; $i++) {
            if ($ranks[$i + 1] - $ranks[$i] !== 1) {
                return false;
            }
        }
        return true;
    }

    private function isThreeOfAKind($ranks)
    {
        $counts = array_count_values($ranks);
        return in_array(3, $counts);
    }

    private function isTwoPair($ranks)
    {
        $counts = array_count_values($ranks);
        return count(array_filter($counts, function($count) { return $count === 2; })) === 2;
    }

    private function isOnePair($ranks)
    {
        $counts = array_count_values($ranks);
        return in_array(2, $counts);
    }

    private function determineWinner($playerHands)
    {
        $highestRank = 0;
        $winners = [];
        
        // 最高ランクを見つける
        foreach ($playerHands as $playerKey => $hand) {
            if ($hand['rank'] > $highestRank) {
                $highestRank = $hand['rank'];
                $winners = [$playerKey];
            } elseif ($hand['rank'] === $highestRank) {
                $winners[] = $playerKey;
            }
        }
        
        // 同じ役の場合はカードの強さで比較
        if (count($winners) > 1) {
            return $this->breakTie($winners, $playerHands);
        }
        
        return $winners[0];
    }

    private function breakTie($winners, $playerHands)
    {
        // 同じ役の場合の比較ロジック
        $firstHand = $playerHands[$winners[0]];
        $handType = $firstHand['type'];
        
        switch ($handType) {
            case 'フォーカード':
                return $this->breakTieByGroupStrength($winners, $playerHands, 4);
            case 'フルハウス':
            case 'スリーカード':
                return $this->breakTieByGroupStrength($winners, $playerHands, 3);
            case 'ツーペア':
                return $this->breakTieByTwoPair($winners, $playerHands);
            case 'ワンペア':
                return $this->breakTieByGroupStrength($winners, $playerHands, 2);
            default:
                return $this->breakTieByHighCard($winners, $playerHands);
        }
    }

    private function breakTieByGroupStrength($winners, $playerHands, $groupSize)
    {
        $highestValue = 0;
        $finalWinners = [];
        
        foreach ($winners as $playerKey) {
            $ranks = array_column($playerHands[$playerKey]['cards'], 'rank');
            $counts = array_count_values($ranks);
            $groupValue = array_search($groupSize, $counts);
            
            if ($groupValue > $highestValue) {
                $highestValue = $groupValue;
                $finalWinners = [$playerKey];
            } elseif ($groupValue === $highestValue) {
                $finalWinners[] = $playerKey;
            }
        }
        
        return count($finalWinners) === 1 ? $finalWinners[0] : $this->breakTieByHighCard($finalWinners, $playerHands);
    }

    private function breakTieByTwoPair($winners, $playerHands)
    {
        $highestPairs = [];
        
        foreach ($winners as $playerKey) {
            $ranks = array_column($playerHands[$playerKey]['cards'], 'rank');
            $counts = array_count_values($ranks);
            $pairs = array_keys(array_filter($counts, function($count) { return $count === 2; }));
            rsort($pairs);
            $highestPairs[$playerKey] = $pairs;
        }
        
        // 高い方のペアを比較
        $highestPairValue = max(array_column($highestPairs, 0));
        $winnersHighPair = array_filter($winners, function($playerKey) use ($highestPairs, $highestPairValue) {
            return $highestPairs[$playerKey][0] === $highestPairValue;
        });
        
        if (count($winnersHighPair) === 1) {
            return reset($winnersHighPair);
        }
        
        // 低い方のペアを比較
        $highestLowPairValue = max(array_column($highestPairs, 1));
        $finalWinners = array_filter($winnersHighPair, function($playerKey) use ($highestPairs, $highestLowPairValue) {
            return $highestPairs[$playerKey][1] === $highestLowPairValue;
        });
        
        return count($finalWinners) === 1 ? reset($finalWinners) : $this->breakTieByHighCard(array_values($finalWinners), $playerHands);
    }

    private function breakTieByHighCard($winners, $playerHands)
    {
        foreach ($winners as $playerKey) {
            $ranks = array_column($playerHands[$playerKey]['cards'], 'rank');
            rsort($ranks);
            $playerHands[$playerKey]['highCards'] = $ranks;
        }
        
        for ($i = 0; $i < 5; $i++) {
            $highestCard = max(array_column(array_intersect_key($playerHands, array_flip($winners)), ['highCards', $i]));
            $remainingWinners = array_filter($winners, function($playerKey) use ($playerHands, $highestCard, $i) {
                return $playerHands[$playerKey]['highCards'][$i] === $highestCard;
            });
            
            if (count($remainingWinners) === 1) {
                return reset($remainingWinners);
            }
            $winners = array_values($remainingWinners);
        }
        
        // 完全な同点の場合は最初のプレイヤーを返す
        return $winners[0];
    }
}