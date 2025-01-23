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

        public function calculateScore()
        {
            $game = session.get('game');

            $player1_hand = $this->handEvaluator->evaluateHand($game['player'][0]);
            $player2_hand = $this->handEvaluator->evaluateHand($game['player'][1]);
            $player3_hand = $this->handEvaluator->evaluateHand($game['player'][2]);
            $player4_hand = $this->handEvaluator->evaluateHand($game['player'][3]);

            $winners = $this->checkWinner($player1_hand, $player2_hand, $player3_hand, $player4_hand);

            // $winnersを元に点数加算ロジック

            


            return 0;
        }

            private function checkWinner($player1_hand, $player2_hand, $player3_hand, $player4_hand)
            {
                $handScores = [
                    'Royal Flush' => 10,
                    'Straight Flush' => 9,
                    'Four of a Kind' => 8,
                    'Full House' => 7,
                    'Flush' => 6,
                    'Straight' => 5,
                    'Three of a Kind' => 4,
                    'Two Pair' => 3,
                    'One Pair' => 2,
                    'High Card' => 1,
                ];

                $playerScores = [
                    'player1'=> $handScores[$player1_hand] ?? 0,
                    'player2'=> $handScores[$player2_hand] ?? 0,
                    'player3'=> $handScores[$player3_hand] ?? 0,
                    'player4'=> $handScores[$player4_hand] ?? 0,
                ];

                $winners = array_keys($playerScores, $maxScore);

                return $winners;
            }

    }