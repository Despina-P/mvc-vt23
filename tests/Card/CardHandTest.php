<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Return the current hand, with added card.
     */
    public function testAddCard(): void
    {
        $card1 = new Card('♥', '2');
        $card2 = new Card('♦', '6');
        $hand = new CardHand();
        $hand->add($card1);
        $hand->add($card2);
        $this->assertEquals([$card1, $card2], $hand->getCards());
    }

    /**
     * Return the number of cards in the hand, with added card.
     */
    public function testGetNumberCards(): void
    {
        $card1 = new Card('♥', '2');
        $card2 = new Card('♦', '6');
        $hand = new CardHand();
        $hand->add($card1);
        $hand->add($card2);
        $this->assertEquals(2, $hand->getNumberCards());
    }

    /**
     * Return the current hand, with added card.
     */
    public function testGetValuesHand(): void
    {
        $card1 = new Card('♥', '2');
        $card2 = new Card('♦', '6');
        $hand = new CardHand();
        $hand->add($card1);
        $hand->add($card2);
        $this->assertEquals([2, 6], $hand->getValues());
    }

    /**
     * Return the current hand as a string.
     */
    public function testGetString(): void
    {
        $card1 = new Card('♥', 'Q');
        $card2 = new Card('♦', 'K');
        $hand = new CardHand();
        $hand->add($card1);
        $hand->add($card2);
        $expected = ['Q♥', 'K♦'];
        $this->assertEquals($expected, $hand->getString());
    }
}