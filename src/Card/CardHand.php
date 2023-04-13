<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private $hand = [];

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function getCards(): array
    {
        return $this->hand;
    }

    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    // Returnerar en array av värden för alla kort i handen.
    // Funktionen loopar igenom varje kort i handen och hämtar värdet på varje kort och sparar det i en array.
    // Sedan returneras denna array med kortvärden.
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    // Returnerar en array med strängrepresentationer av varje kort i handen.
    // Den initierar en tom array, itererar genom varje kort i handen och
    // lägger till dess strängrepresentation till arrayen.
    // Sedan returnerar funktionen arrayen med strängar.
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }
}
