<?php

namespace App\Card;

/**
 * The class Score is used to calculate the score of each player
 * and to pick a winner in the game twenty-one.
 */
class Score
{
    /**
     * The method calculates the total score of a hand of cards, for each player,
     * based on the cards in the list.
     *
     * @param string $cardsList A string representing a list of cards.
     * @return int The total score of the hand.
     */
    public function calculateHandValue(string $cardsList): int
    {
        preg_match_all('/\[(.*?)\]/', $cardsList, $matches);
        $values = $matches[1];

        $sum = 0;
        foreach ($values as $value) {
            $value = substr($value, 0, 1);
            if ($value == 'J') {
                $sum += 11;
                continue;
            } elseif ($value == 'Q') {
                $sum += 12;
                continue;
            } elseif ($value == 'K') {
                $sum += 13;
                continue;
            } elseif ($value == 'A') {
                $sum += 1;
                continue;
            }
            $sum += (int)$value;
        }
        return $sum;
    }

    /**
     * The method compares the scores of the player and the bankir,
     * and returns a message indicating who is the winner.
     *
     * @param int $playerScore The score of the player's hand.
     * @param int $bankirScore The score of the bankir's hand.
     * @return string The result message indicating the winner.
     */
    public function compareHands(int $playerScore, int $bankirScore): string
    {
        $diff1 = abs($playerScore - 21);
        $diff2 = abs($bankirScore - 21);

        if ($playerScore > 21) {
            return "Bankiren vinner med totalt värde $bankirScore";
        }

        if ($bankirScore > 21) {
            return "Spelaren vinner med totalt värde $playerScore";
        }

        if ($playerScore == $bankirScore) {
            return "Det är oavgjort med totalt värde $playerScore";
        }

        if ($diff1 < $diff2) {
            return "Spelaren vinner med totalt värde $playerScore";
        }
        return "Bankiren vinner med totalt värde $bankirScore";
    }
}
