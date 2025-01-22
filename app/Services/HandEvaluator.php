<?php

    namespace App\Services;

    use App\Models\Player;

    class ScoreService
    {
        public function evaluateHand($cards)
        {
            if ($this->isRoyalFlush($cards)) {
                return 'Royal Flush';
            } elseif ($this->isStraightFlush($cards)) {
                return 'Straight Flush';
            } elseif ($this->isFourOfAKind($cards)) {
                return 'Four of a Kind';
            } elseif ($this->isFullHouse($cards)) {
                return 'Full House';
            } elseif ($this->isFlush($cards)) {
                return 'Flush';
            } elseif ($this->isStraight($cards)) {
                return 'Straight';
            } elseif ($this->isThreeOfAKind($cards)) {
                return 'Three of a Kind';
            } elseif ($this->isTwoPair($cards)) {
                return 'Two Pair';
            } elseif ($this->isOnePair($cards)) {
                return 'One Pair';
            } else {
                return 'High Card';
            }
        }   

        private function isStraightFlush($cards) { return false; }
        private function isFourOfAKind($cards) { return false; }
        private function isFullHouse($cards) { return false; }
        private function isFlush($cards) { return false; }
        private function isStraight($cards) { return false; }
        private function isThreeOfAKind($cards) { return false; }
        private function isTwoPair($cards) { return false; }
        private function isOnePair($cards) { return false; }

    }