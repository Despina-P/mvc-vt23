<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\DeckOfCardsJoker;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class DeckOfCardsJokerTest extends TestCase
{
    /**
     * Get the jokers out of the deck of cards.
     */
    public function testDeckOfCardsJokerContainsJokers(): void
    {
        $deck = new DeckOfCardsJoker();
        $cards = $deck->getCardsSorted();
        $jokers = $deck->getJokers();

        // Kontrollera att det finns två jokrar i kortleken
        $this->assertCount(2, $jokers);

        // Kontrollera att de två jokrarna finns i kortleken
        $this->assertContains($jokers[0], $cards);
        $this->assertContains($jokers[1], $cards);
    }

    public function testDrawFromEmptyDeckOfCardsJokerReturnsNull(): void
    {
        $deck = new DeckOfCardsJoker();

        // Ta bort alla kort från kortleken
        for ($i = 0; $i <= 54; $i++) {
            $deck->draw();
        }

        // Försök att dra ett kort från en tom kortlek med jokrar
        $card = $deck->draw();

        // Kolla att ett nullvärde returneras
        $this->assertNull($card);
    }
}