<?php

namespace App\Card;

class Card
{
    private $suit; //Valör: hjärter, ruter, spader, klöver
    private $value; //Siffra

    public function __construct($suit, $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
