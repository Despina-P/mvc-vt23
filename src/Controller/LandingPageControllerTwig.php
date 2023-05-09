<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\DeckOfCardsJoker;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\Score;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use InvalidArgumentException;

class LandingPageControllerTwig extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }

    //Skapa en route GET api/deck som returnerar en JSON struktur med hela kortleken sorterad per färg och värde.
    #[Route("/api/deck", name: "api_deck", methods:['GET'])]
    public function jsonDeck(): Response
    {
        //Visar samtliga kort i kortleken sorterade per färg och värde
        /** @var DeckOfCards $deck */
        $deck = new DeckOfCards();
        $cards = $deck->getCardsSorted();

        $cardsList = '';
        foreach($cards as $card) {
            $cardsList .= "[{$card->getValue()}{$card->getSuit()}]";
        }
        $data = [
            'cards' => $cardsList
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    //Skapa en route POST api/deck/shuffle som blandar kortleken och därefter returnerar en JSON struktur med kortleken.
    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods:['POST'])]
    public function jsonShuffle(SessionInterface $session): Response
    {
        //Visar samtliga kort i kortleken sorterade per färg och värde
        /** @var DeckOfCards $deck */
        $deck = new DeckOfCards();
        $session->set('deck', $deck);
        $cards = $deck->shuffle();

        $cardsList = '';
        foreach($cards as $card) {
            $cardsList .= "[{$card->getValue()}{$card->getSuit()}]";
        }
        $data = [
            'cards' => $cardsList
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    // Skapa route POST api/deck/draw som drar 1 kort från kortleken och visar upp dem i en JSON struktur samt antalet kort som är kvar i kortleken.
    #[Route("/api/deck/draw", name: "api_deck_draw", methods:['POST'])]
    public function jsonDraw(SessionInterface $session): Response
    {
        //Visar samtliga kort i kortleken sorterade per färg och värde
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');

        $card = $deck->draw();
        if ($card === null) {
            // Handle the case where there are no more cards left in the deck
            return new JsonResponse(['error' => 'No more cards in the deck']);
        }

        $cardList = "[{$card->getValue()}{$card->getSuit()}]";

        $deckSize = $deck->getDeckSize();
        $data = [
            'cards' => $cardList,
            'deckSize' => $deckSize
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_deck_draw_multiple", methods:['POST'])]
    public function jsonDrawMultiple(
        int $number,
        SessionInterface $session
    ): Response {
        //Visar samtliga kort i kortleken sorterade per färg och värde
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        $cards = $deck->shuffle();

        if ($number < 1 || $number > $deck->getDeckSize()) {
            throw new InvalidArgumentException("Invalid number of cards to draw.");
        }

        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            $drawnCards[] = array_pop($deck->cards);
        }

        $deckSize = $deck->getDeckSize();

        $cardsList = '';
        foreach($cards as $card) {
            $cardsList .= "[{$card->getValue()}{$card->getSuit()}]";
        }

        $data = [
            'cards' => $cardsList,
            'deckSize' => $deckSize
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/game", name: "api_game", methods:['GET'])]
    public function jsonGame(
        SessionInterface $session
    ): Response {
        $playerScore = $session->get('playerScore');
        $cardsListPlayer = $session->get('cardsListPlayer');
        $bankirScore = $session->get('bankirScore');
        $cardsListBankir = $session->get('cardsListBankir');

        $data = [
            'cardsListPlayer' => $cardsListPlayer,
            'playerScore' => $playerScore,
            'cardsListBankir' => $cardsListBankir,
            'bankirScore' => $bankirScore,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
