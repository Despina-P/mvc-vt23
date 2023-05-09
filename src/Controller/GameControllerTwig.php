<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\DeckOfCardsJoker;
use App\Card\CardHand;
use App\Card\DeckOfCards;

use App\Card\Score;
use App\Card\Player;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use InvalidArgumentException;

class GameControllerTwig extends AbstractController
{
    #[Route("/game", name: "game_start")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDocumentation(): Response
    {
        return $this->render('game/doc.html.twig');
    }


    #[Route("/game/init", name: "init")]
    public function init(
        SessionInterface $session
    ): Response {
        /** @var DeckOfCards $deck */
        //Skapa en ny kortlek, blanda den och spara den i session
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        //Spelaren får två kort
        //Skapa tom sträng
        $cardsListPlayer = '';
        //Dra kort två gggr
        for ($i = 0; $i < 2; $i++) {
            $card = $deck->draw();
            //Skriv ut korten och lägg till i strängen
            if ($card !== null) {
                $cardsListPlayer .= "[{$card->getValue()}{$card->getSuit()}]";
            }
        }
        $session->set('cardsListPlayer', $cardsListPlayer);

        $scoreP = new Score();
        $playerScore = $scoreP->calculateHandValue($cardsListPlayer);
        $session->set('playerScore', $playerScore);

        //Bankir får två kort
        //Skapa tom sträng
        $cardsListBankir = '';
        //Dra kort två gggr
        for ($i = 0; $i < 2; $i++) {
            $card = $deck->draw();
            //Skriv ut korten och lägg till i strängen
            if ($card !== null) {
                $cardsListBankir .= "[{$card->getValue()}{$card->getSuit()}]";
            }
        }
        $session->set('cardsListBankir', $cardsListBankir);
        //var_dump($cardsListBankir);

        //Beräkna storleken på kortleken och spara det i session
        $deckSize = $deck->getDeckSize();
        $session->set('deckSize', $deckSize);

        //Beräkna bankirens poäng
        $score = new Score();
        $bankirScore = $score->calculateHandValue($cardsListBankir);
        $session->set('bankirScore', $bankirScore);

        $data = [
            'cardsListPlayer' => $cardsListPlayer,
            'cardsListBankir' => $cardsListBankir,
            'deckSize' => $deckSize,
            'bankirScore' => $bankirScore,
        ];

        return $this->render('game/play.html.twig', $data);
    }

    // Route för att låta bankiren dra kort tills dess summan är 17 eller högre
    #[Route("/game/play/bankir/draw", name: "bankir_draw")]
    public function bankirDraw(
        SessionInterface $session
    ): Response {
        $score = new Score();

        // Hämta kortleken och bankirens kortlista från sessionen
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var string $cardsListBankir The string representation of the bankir's hand. */
        $cardsListBankir = $session->get('cardsListBankir');
        $bankirScore = $score->calculateHandValue($cardsListBankir);

        // Dra kort tills dess summan är 17 eller högre
        while ($bankirScore < 17) {
            $card = $deck->draw();
            if ($card !== null) {
                $cardsListBankir .= "[{$card->getValue()}{$card->getSuit()}]";
                $bankirScore = $score->calculateHandValue($cardsListBankir);
                //var_dump($bankirScore);
            }
        }

        // Uppdatera sessionen med bankirens nya kortlista och summa
        $session->set('cardsListBankir', $cardsListBankir);
        $session->set('bankirScore', $bankirScore);

        //Beräkna storleken på kortleken och spara det i session
        $deckSize = $session->get('deckSize');
        $deckSize = $deck->getDeckSize();
        $session->set('deckSize', $deckSize);

        $data = [
            'bankirScore' => $bankirScore,
            'cardsListBankir' => $cardsListBankir,
            'deckSize' => $deckSize
        ];
        // Omdirigera tillbaka till "/game/play"
        return $this->redirectToRoute('end', $data);
    }

    #[Route("/game/play", name: "play")]
    public function play(
        SessionInterface $session
    ): Response {
        // var_dump($session->get('cardsListPlayer'));
        // var_dump($session->get('cardsListBankir'));
        // var_dump($session->get('bankirScore'));
        $data = [
            'cardsListPlayer' => $session->get('cardsListPlayer'),
            'cardsListBankir' => $session->get('cardsListBankir'),
            'deckSize' => $session->get('deckSize'),
            'bankirScore' => $session->get('bankirScore'),
        ];


        return $this->render('game/play.html.twig', $data);
    }

    #[Route("/game/play/end", name: "end")]
    public function end(
        SessionInterface $session
    ): Response {
        $score = new Score();

        //Hämta listan av kort och beräkna summan
        /** @var string $cardsListPlayer A string containing the list of cards in the player's hand.*/
        $cardsListPlayer = $session->get('cardsListPlayer');
        //var_dump($cardsListPlayer);

        $playerScore = $score->calculateHandValue($cardsListPlayer);
        $session->set('playerScore', $playerScore);
        //var_dump($playerScore);

        /** @var int $bankirScore The total hand value of the banker's hand.*/
        $bankirScore = $session->get('bankirScore');
        //var_dump($session->get('cardsListBankir'));
        //var_dump($bankirScore);

        $deckSize = $session->get('deckSize');

        $winner = $score->compareHands($playerScore, $bankirScore);
        //var_dump($winner);

        $data = [
            'winner' => $winner,
            'deckSize' => $deckSize,
            'bankirScore' => $bankirScore,
            'playerScore' => $playerScore,
            'cardsListPlayer' => $cardsListPlayer,
            'cardsListBankir' => $session->get('cardsListBankir'),
        ];

        return $this->render('game/play.html.twig', $data);
    }

    #[Route("/game/play/draw", name: "draw")]
    public function draw(
        SessionInterface $session
    ): Response {
        //Hämta kortleken mha session
        $deck = $session->get('deck');

        //Hämta spelarens kort mha sessionen
        $cardsListPlayer = $session->get('cardsListPlayer');

        //Dra ett nytt kort
        /** @var DeckOfCards $deck */
        $card = $deck->draw();

        //Lägg till kortet i handen på spelaren
        if ($card !== null) {
            $cardsListPlayer .= "[{$card->getValue()}{$card->getSuit()}]";

            //Uppdatera sessionen
            $session->set('cardsListPlayer', $cardsListPlayer);
        }

        //Beräkna storleken på kortleken och spara det i session
        $deckSize = $session->get('deckSize');
        $deckSize = $deck->getDeckSize();
        $session->set('deckSize', $deckSize);

        $data = [
            'cardsListPlayer' => $cardsListPlayer,
            'deckSize' => $deckSize
        ];

        return $this->redirectToRoute('play', $data);
    }

}
