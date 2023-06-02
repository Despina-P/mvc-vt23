<?php

namespace App\Card;

class DeckOfCards
{
    /**
     * @var Card[]
     */
    public array $cards;

    public function __construct()
    {
        //Initiera $cards till en tom array
        $this->cards = array();

        //Initiera kortleken med 52 kort
        $suits = array('♥', '♦', '♣', '♠');
        $values = array('A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K');

        foreach($suits as $suit) {
            foreach($values as $value) {
                $this->cards[] = new Card($suit, (string)$value);
            }
        }
    }

    public function draw(): ?Card
    {
        return array_pop($this->cards);
    }

    public function drawTwoCards(): string
    {
        $listOfCards = '';
        //Dra två stycken kort
        for ($i = 0; $i < 2; $i++) {
            $card = $this->draw();
            //Skriv ut korten och lägg till i strängen
            if ($card !== null) {
                $listOfCards .= $card->getCard();
            }
        }
        return $listOfCards;
    }

    /**
     * @return Card[]
     */
    public function shuffle(): array
    {
        shuffle($this->cards);
        return $this->cards;
    }

    /**
     * @return Card[]
     */
    public function getCardsSorted(): array
    {
        $cards = $this->cards;
        // usort(); sort an array by values using a user definied comparison function
        usort($cards, function ($argumenta, $argumentb) {
            $suitOrder = ['♥', '♦', '♣', '♠'];
            $suitComparison = array_search($argumenta->getSuit(), $suitOrder) - array_search($argumentb->getSuit(), $suitOrder);
            if ($suitComparison === 0) {
                $valueOrder = ['A', 2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K'];
                return array_search($argumenta->getValue(), $valueOrder) - array_search($argumentb->getValue(), $valueOrder);
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
