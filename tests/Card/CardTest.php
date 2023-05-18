<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Get the suit of the card.
     */
    public function testGetSuit(): void
    {
        $card = new Card('♥', '3');
        $this->assertEquals('♥', $card->getSuit());
    }

    /**
     * Get the value of the card.
     */
    public function testGetValue(): void
    {
        $card = new Card('♥', '3');
        $this->assertEquals('3', $card->getValue());
    }

    /**
     * Get the card as a string.
     */
    public function testGetAsString(): void
    {
        $card = new Card('♥', '3');
        $this->assertEquals('3♥', $card->getAsString());
    }
}