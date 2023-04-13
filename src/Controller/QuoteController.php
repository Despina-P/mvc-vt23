<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class QuoteController
{
    #[Route("/api/quote", name: "quote")]
    public function jsonQuote(): Response
    {
        #Lista med citat
        $quotes = [
            'Ett glatt hjärta får ansiktet att lysa upp, men hjärtesorg slår ner modet.',
            'För den betryckte är alla dagar mörka, men för den som är väl till mods är varje dag en fest.',
            'Glad blir den som ger ett bra svar, ja, vad är bättre än ett ord i rätt tid!',
            'Ett mjukt svar stillar raseri, men hårda ord väcker vrede.',
            'De visa använder sin tunga med kunskap, men ur den oförståndiges mun flödar dumheter.'
        ];

        #Slumpmässigt välja ett citat
        $randomQuote = $quotes[array_rand($quotes)];

        #Göra en array med citat, dagens datum, tidstämpel för när svaret genererades
        $data = [
            'quote' => $randomQuote,
            'date' => date('Y-m-d'),
            'timestamp' => time()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
