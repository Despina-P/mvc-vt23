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
     * @param mixed $cardsList A string representing a list of cards.
     * @return int The total score of the hand.
     */
    public function calculateHandValue(mixed $cardsList): int
    {
        preg_match_all('/\[(.*?)\]/', $cardsList, $matches);
    $values = $matches[1];

    $sum = 0;
    foreach ($values as $value) {
        $cardValue = substr($value, 0, -3);

        if ($cardValue == 'J') {
            $sum += 11;
        } elseif ($cardValue == 'Q') {
            $sum += 12;
        } elseif ($cardValue == 'K') {
            $sum += 13;
        } elseif ($cardValue == 'A') {
            $sum += 1;
        } elseif ($cardValue == '10') {
            $sum += 10;
        }
        $sum += (int) $cardValue;
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

    /**
     * The method compares the scores of the player and the bankir,
     * and returns a message indicating who is the winner.
     *
     * @param array $playerScores The score of the players hand.
     * @param int $bankirScore The score of the bankir's hand.
     * @return string The result message indicating the winner.
     */
    public function projectCompareHands(array $playerScores, int $bankirScore): string
    {
        $winningHandIndex = 1;
        $highestScore = $playerScores[0];
        $isDraw = false;
        $amountOfHands = count($playerScores);

        for ($i = 1; $i < $amountOfHands; $i++) {
            $playerScore = $playerScores[$i];

            if ($playerScore > $highestScore && $playerScore <= 21) {
                // Hittat en ny högsta poäng
                $winningHandIndex = $i;
                $highestScore = $playerScore;
                $isDraw = false;
            } elseif ($playerScore == $highestScore && $playerScore <= 21) {
                // Oavgjort mellan flera händer
                $isDraw = true;
            }
        }

        if ($bankirScore > 21) {
            // Bankiren förlorar om poängen överstiger 21
            return "Spelaren vinner med hand$winningHandIndex och totalt värde $highestScore.";
        }

        if ($isDraw) {
            // Oavgjort mellan flera händer
            return "Det är oavgjort mellan flera händer med totalt värde $highestScore.";
        }

        // Bankiren vinner om ingen annan vinnare hittades
        return "Bankiren vinner hand$winningHandIndex med totalt värde $bankirScore.";
    }

}
