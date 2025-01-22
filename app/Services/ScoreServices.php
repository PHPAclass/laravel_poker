<?php

    namespace App\Services;

    use App\Models\Player;
    use App\Services\HandEvaluator;

    class ScoreService
    {
        protected $handEvaluator;

        public function __construct(HandEvaluator $handEvaluator)
        {
            $this->handEvaluator = $handEvaluator;
        }

        public function calculateScore($gameId, $playerId, $cards)
        {
            $hand = $this->handEvaluator->evaluateHand($cards);

            $scoreValue = $this->getScoreForHand($hand);

            $player = Player::where('game_id', $gameId)->where('player_id', $playerId)->first();

            $player->score += $scoreValue; 
            $player->save();

            return 0;
        }

        private function getScoreForHand($hand)
        {
            $handScores = [
                'Royal Flush' => 100,
                'Straight Flush' => 75,
                'Four of a Kind' => 50,
                'Full House' => 40,
                'Flush' => 30,
                'Straight' => 20,
                'Three of a Kind' => 10,
                'Two Pair' => 5,
                'One Pair' => 2,
                'High Card' => 1,
            ];

            return $handScores[$hand] ?? 0;
        }

    }