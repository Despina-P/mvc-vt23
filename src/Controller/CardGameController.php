<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\DeckOfCardsJoker;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use InvalidArgumentException;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $session->set('deck', $deck);
        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(
        SessionInterface $session
    ): Response {
        //Visar samtliga kort i kortleken sorterade per färg och värde
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        $cards = $deck->getCardsSorted();

        $data = [
            'cards' => $cards
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function cardDeckShuffle(
        SessionInterface $session
    ): Response {
        //Visar samtliga kort i kortleken när den har blandats
        //Skapa en instans av DeckOfCards klassen
        $deck = new DeckOfCards();
        $session->set('deck', $deck);

        $cards = $deck->shuffle();

        $data = [
            'cards' => $cards
        ];

        return $this->render('card/shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function cardDeckDraw(
        SessionInterface $session
    ): Response {
        //Drar ett kort från kortleken och visar upp det.
        //Visar även antalet kort som är kvar i kortleken
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        $card = $deck->draw();
        $deckSize = $deck->getDeckSize();

        $data = [
            'card' => $card,
            'deckSize' => $deckSize
        ];

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "card_deck_draw_multiple")]
    public function cardDeckDrawMultiple(
        int $number,
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        $deck->shuffle();

        if ($number < 1 || $number > $deck->getDeckSize()) {
            throw new InvalidArgumentException("Invalid number of cards to draw.");
        }


        $cards = [];
        for ($i = 0; $i < $number; $i++) {
            $cards[] = array_pop($deck->cards);
        }

        $deckSize = $deck->getDeckSize();

        $data = [
            'cards' => $cards,
            'deckSize' => $deckSize
        ];

        return $this->render('card/draw_multiple.html.twig', $data);
    }

}
