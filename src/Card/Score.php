<?php

namespace App\Card;

class Score
{
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
