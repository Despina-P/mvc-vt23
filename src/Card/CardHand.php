<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /**
    * @var Card[]
    */
    private array $hand = [];

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    /**
     * @return Card[]
     */
    public function getCards(): array
    {
        $cards = $this->hand;
        return $cards;
    }

    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    // Returnerar en array av värden för alla kort i handen.
    // Funktionen loopar igenom varje kort i handen och hämtar värdet på varje kort och sparar det i en array.
    // Sedan returneras denna array med kortvärden.
    /**
     * @return string[]
     */
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
    /**
     * @return string[]
     */
    public function getString(): array
    {
        /** @var string[] $values */
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }
}
