<?php

namespace App\Card;

class DeckOfCards
{
    public $cards;

    public function __construct()
    {
        //Initiera $cards till en tom array
        $this->cards = array();

        //Initiera kortleken med 52 kort
        $suits = array('♥', '♦', '♣', '♠');
        $values = array('A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'K', 'Q');

        foreach($suits as $suit) {
            foreach($values as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    public function draw($number = 1)
    {
        return array_pop($this->cards);
    }

    public function shuffle()
    {
        shuffle($this->cards);
        return $this->cards;
    }

    public function getCardsSorted()
    {
        $cards = $this->cards;
        // usort(); sort an array by values using a user definied comparison function
        usort($cards, function ($a, $b) {
            $suitOrder = ['♥', '♦', '♣', '♠'];
            $suitComparison = array_search($a->getSuit(), $suitOrder) - array_search($b->getSuit(), $suitOrder);
            if ($suitComparison === 0) {
                $valueOrder = ['A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'];
                return array_search($a->getValue(), $valueOrder) - array_search($b->getValue(), $valueOrder);
            }
            return $suitComparison;
        });
        return $cards;
    }

    public function getDeckSize(): int
    {
        return count($this->cards);
    }
}
