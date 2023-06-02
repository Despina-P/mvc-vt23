<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Score;

/**
 * Test cases for class Card.
 */
class ScoreTest extends TestCase
{
    /**
     * Get the score of the current hand.
     */
    public function testCalculateHandValue(): void
    {
        $score = new Score();

        // Testa om det fungerar för en hand som innehåller bara siffror
        $this->assertEquals(9, $score->calculateHandValue('[3♥][6♦]'));

        // Testa om det fungerar för en hand som inte innehåller siffror
        $this->assertEquals(24, $score->calculateHandValue('[J♥][Q♦][A♦]'));
    }

    /**
     * Get the winner of the game.
     */
    public function testCompareHands(): void
    {
        $score = new Score();

        // Testa att bankir vinner
        $this->assertEquals("Bankiren vinner med totalt värde 20", $score->compareHands(25, 20));

        // Testa att spelaren vinner
        $this->assertEquals("Spelaren vinner med totalt värde 18", $score->compareHands(18, 16));
    }

}