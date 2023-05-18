<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCards;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Get the deck of cards in a shuffled order.
     */
    public function testShuffleReturnsShuffledDeck(): void
    {
        $deck = new DeckOfCards();
        $shuffledDeck = $deck->shuffle();
        $this->assertNotEquals($deck, $shuffledDeck);
    }

    /**
     * Get the deck of cards in a shuffled order.
     */
    public function testDrawFromEmptyDeckReturnsNull(): void
    {
        $deck = new DeckOfCards();
        for ($i = 0; $i <= 52; $i++) {
            $deck->draw();
        }
        $this->assertNull($deck->draw());
    }

    /**
     * Get deck size.
     */
    public function testGetDeckSize(): void
    {
        $deck = new DeckOfCards();
        $deck->draw();
        $this->assertEquals(51, $deck->getDeckSize());
    }
}