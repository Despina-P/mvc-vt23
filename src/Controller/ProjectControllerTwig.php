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

class ProjectControllerTwig extends AbstractController
{
    #[Route("/proj", name: "project_start")]
    public function projectStart(): Response
    {
        return $this->render('project/home.html.twig');
    }

    #[Route("/proj/doc", name: "project_doc")]
    public function projectDoc(): Response
    {
        return $this->render('project/doc.html.twig');
    }

    #[Route("/proj/about", name: "project_about")]
    public function projectAbout(): Response
    {
        return $this->render('project/about.html.twig');
    }


    #[Route("/proj/init", name: "project_init", methods: ['POST'])]
    public function projectInit(
        SessionInterface $session,
        Request $request
    ): Response {
        $player = $request->request->get('player'); //Hämta spelarens namn från formuläret
        $session->set('player', $player); //Spara spelarens namn i sessionen
        //var_dump($player);

        $numberOfHands = $request->request->getInt('numberOfHands'); //Hämta antalet händer från formuläret
        $session->set('numberOfHands', $numberOfHands); // Spara antalet händer i sessionen
        //var_dump($numberOfHands);
        $startNumberOfHands = $request->request->getInt('numberOfHands');
        $session->set('startNumberOfHands', $startNumberOfHands);

        $currentHand = 1; // Starta med hand 1

        // Spara currentHand i sessionen
        $session->set('currentHand', $currentHand);

        /** @var DeckOfCards $deck */
        $deck = new DeckOfCards(); //Skapa en ny kortlek
        $deck->shuffle(); //Blanda kortleken
        $session->set('deck', $deck); //Spara i session

        $hands = [];

        // Loopa igenom antalet händer och skapa och hantera varje hand
        for ($i = 1; $i <= $numberOfHands; $i++) {
            $hand = $deck->drawTwoCards();
            $score = new Score();
            $scoreHand = $score->calculateHandValue($hand);

            // Sätt variablerna och spara dem i sessionen
            $hands["hand{$i}"] = $hand;
            $hands["scoreHand{$i}"] = $scoreHand;
            $session->set("hand{$i}", $hand);
            $session->set("scoreHand{$i}", $scoreHand);
            $session->set("ended_hand{$i}", false);
            $hands["ended_hand{$i}"] = false;

            //var_dump($hands);
        }

        //var_dump($hands);

        $cardsBankir = $deck->drawTwoCards(); //Bankiren får två kort
        $session->set('cardsBankir', $cardsBankir);
        //Räkna bankirens poäng
        $scoreB = new Score();
        $scoreBankir = $scoreB->calculateHandValue($cardsBankir);

        //Beräkna storleken på kortleken och spara det i session
        $deckSize = $deck->getDeckSize();
        $session->set('deckSize', $deckSize);


        $data = [
            'deckSize' => $deckSize,
            'scoreBankir' => $scoreBankir,
            'cardsBankir' => $cardsBankir,
            'numberOfHands' => $numberOfHands,
            'currentHand' => $currentHand,
            'startNumberOfHands' => $startNumberOfHands
        ];
    
        // Loopa igenom $hands-arrayen för att sätta variablerna i $data
        foreach ($hands as $key => $value) {
            $data[$key] = $value;
        }

        // Spara $data i sessionen med nyckeln 'data'
        $session->set('data', $data);

        var_dump($data);

        return $this->render('project/play.html.twig', [
            'data' => $data,
        ]);
    }


    #[Route("/proj/play/draw/{hand}", name: "project_draw", methods: ['POST', 'GET'])]
    public function projectDraw(
        SessionInterface $session,
        Request $request,
        string $hand
    ): Response {
        $data = $session->get('data');
        var_dump($data);
        $deck = $session->get('deck');
        $handKey = $hand;
        //var_dump($handKey);
        $handCards = $session->get($handKey);
        $currentHand = $session->get('currentHand');
        $numberOfHands = $session->get('numberOfHands');

        var_dump($data["ended_{$handKey}"]);
        // Dra ett nytt kort om spelaren inte har klickat på "End"
        if (in_array(false, $data, true)) {
            //Dra ett nytt kort
            $card = $deck->draw();

            //Lägg till kortet i handen på spelaren
            if ($card !== null) {
                $handCards .= $card->getCard();

                //Uppdatera sessionen
                $session->set($handKey, $handCards);
                //var_dump($handKey);
                //var_dump($handCards);
            }

            // Uppdatera poängen för den aktuella spelaren
            $score = new Score();
            $scoreKey = "scoreHand{$currentHand}";
            $scoreHand = $score->calculateHandValue($handCards);
            $session->set($scoreKey, $scoreHand);
            $session->set($handKey, $handCards);

            var_dump($currentHand);
            // Byt till nästa hand
            if ($currentHand < $numberOfHands) {
                // Återställ currentHand till 1 om det redan är lika med eller större än numberOfHands
                $currentHand++;
            } else {
                // Öka currentHand med 1 om det är mindre än numberOfHands
                $currentHand = 1;
            }
            $session->set('currentHand', $currentHand);
            var_dump($currentHand);
        }

        //Beräkna storleken på kortleken och spara det i session
        $deckSize = $deck->getDeckSize();
        $session->set('deckSize', $deckSize);

        $numberOfHands = $session->get('numberOfHands');

        for ($i = 1; $i <= $numberOfHands; $i++) {
            $handKey = "hand{$i}";
            $scoreKey = "scoreHand{$i}";
            $endedKey = "ended_{$handKey}";

            $handCards = $session->get($handKey);
            $scoreValue = $session->get($scoreKey);
            $ended = $session->get($endedKey);

            $data[$handKey] = $handCards;
            $data[$scoreKey] = $scoreValue;
            $data[$endedKey] = $ended;
        }

        $data['deckSize'] = $deckSize;
        $data['numberOfHands'] = $numberOfHands;
        $data['currentHand'] = $currentHand;

        // Spara $data i sessionen med nyckeln 'data'
        $session->set('data', $data);
        var_dump($data);

        return $this->render('project/play.html.twig', [
            'hand' => $hand,
            'data' => $data
        ]);
    }

    #[Route("/proj/play/end/{hand}", name: "project_end", methods: ['POST'])]
    public function projectEnd(
        SessionInterface $session,
        Request $request,
        string $hand
    ): Response {
        // Hämta spelarens hand från sessionen
        $handKey = $hand;
        $handCards = $session->get($handKey);

        // Beräkna poängen för spelarens hand
        $score = new Score();
        $scoreValue = $score->calculateHandValue($handCards);

        // Spara poängen i sessionen för spelaren
        $scoreKey = "score_{$handKey}";
        $session->set($scoreKey, $scoreValue);

        // Uppdatera $data-arrayen med nyckeln "ended_hand1" och sätta värdet till true
        $session->set("ended_{$handKey}", true);
        $data["ended_{$handKey}"] = true;

        $deckSize = $session->get('deckSize');
        $numberOfHands = $session->get('numberOfHands');
        $numberOfHands--;
        $session->set('numberOfHands', $numberOfHands);
        $currentHand = $session->get('currentHand');
        var_dump($currentHand);

        // Byt till nästa hand
        if ($currentHand < $numberOfHands) {
            // Återställ currentHand till 1 om det redan är lika med eller större än numberOfHands
            $currentHand++;
        } else {
            // Öka currentHand med 1 om det är mindre än numberOfHands
            $currentHand = 1;
        }
        $session->set('currentHand', $currentHand);
        var_dump($currentHand);
        $data = [
            'deckSize' => $deckSize,
            'numberOfHands' => $numberOfHands,
            'currentHand' => $currentHand
        ];

        for ($i = 1; $i <= $numberOfHands; $i++) {
            $handKey = "hand{$i}";
            $scoreKey = "scoreHand{$i}";
            $endedKey = "ended_{$handKey}";

            $handCards = $session->get($handKey);
            $scoreValue = $session->get($scoreKey);
            $ended = $session->get($endedKey);

            $data[$handKey] = $handCards;
            $data[$scoreKey] = $scoreValue;
            $data[$endedKey] = $ended;
        }

        // Spara $data i sessionen med nyckeln 'data'
        $session->set('data', $data);
        var_dump($data);
        var_dump($handKey);
        if (in_array(false, $data, true)) {
            // Det finns en spelare som inte har avslutat
            return $this->render('project/play.html.twig', [
                'hand' => $handKey,
                'data' => $data
            ]);
        }
        return $this->redirectToRoute('project_finished', [
            'data' => $data
        ]);
    }


    #[Route("proj/finished", name: "project_finished", methods: ['GET'])]
    public function projectFinished(
        SessionInterface $session
    ): Response {

        $score = new Score();

        // Hämta kortleken och bankirens kortlista från sessionen
        /** @var DeckOfCards $deck */
        $deck = $session->get('deck');
        /** @var string $cardsBankir The string representation of the bankir's hand. */
        $cardsBankir = $session->get('cardsBankir');
        $scoreBankir = $score->calculateHandValue($cardsBankir);

        // Dra kort tills dess summan är 17 eller högre
        while ($scoreBankir < 17) {
            $card = $deck->draw();
            if ($card !== null) {
                $cardsBankir .= $card->getCard();
                $scoreBankir = $score->calculateHandValue($cardsBankir);
                //var_dump($scoreBankir);
            }
        }

        // Uppdatera sessionen med bankirens nya kortlista och summa
        $session->set('cardsBankir', $cardsBankir);
        $session->set('scoreBankir', $scoreBankir);

        $scoreP = new Score();

        // Hämta antalet händer från sessionen
        $startNumberOfHands = $session->get('startNumberOfHands');
        var_dump($startNumberOfHands);
        // Skapa en array för att lagra spelarnas poäng
        $playerScores = [];

        // Hämta spelarnas poäng för varje hand
        for ($i = 1; $i <= $startNumberOfHands; $i++) {
            $scoreKey = "scoreHand{$i}";
            $playerScore = $session->get($scoreKey);
            $playerScores[] = $playerScore;
        }

        var_dump($playerScores);

        // Anropa projectCompareHands med spelarnas poäng och bankirens poäng
        $winner = $score->projectCompareHands($playerScores, $scoreBankir);
        var_dump($winner);

        return $this->render('project/finished.html.twig', [
            'winner' => $winner
        ]);
    }
}