<?php

namespace App\Card;

class Card
{
    private string $suit; //Valör: hjärter, ruter, spader, klöver
    private string $value; //Siffra
    private string $card;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->card = "[{$value}{$suit}]";
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCard(): string
    {
        return $this->card;
    }

    public function getAsString(): string
    {
        return $this->value . $this->suit;
    }

}
