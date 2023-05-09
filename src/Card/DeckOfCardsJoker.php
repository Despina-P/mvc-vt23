<?php

namespace App\Card;

class DeckOfCardsJoker extends DeckOfCards
{
    /**
     * @var Card[]
     */
    private array $jokers;

    public function __construct()
    {
        parent::__construct();
        $this->jokers = array(new Card('Joker', 'Joker'), new Card('Joker', 'Joker'));
        $this->cards = array_merge($this->cards, $this->jokers);
    }

    /**
     * @return Card[]
     */
    public function getJokers(): array
    {
        return $this->jokers;
    }

    public function getDeckSize(): int
    {
        return count($this->cards) - count($this->jokers);
    }
}
