// // Skapa en kortleck
// $deck = new DeckOfCards();

// //Blanda kortleken
// $deck->shuffle();

// //Dra två kort ur högen till spelaren
// $numberOfCards = 2;

// $playerHand = $deck->draw($numberOfCards);

// // Dra två kort ur högen till bankir
// $bankerHand = $deck->draw($numberOfCards);

// //Beräkna poäng för en hand
// function calcScore($hand) {
//     let $points = 0;
//     let $ace = 0;

//     //Loopa igenom alla kort i handen
//     for (let i = 0; i < $hand.length; i++) {
//         //Lägg till kortets poäng i den totala poängen
//         let $card = $hand[i]

//         //Om kortet är ace, öka ace med ett
//         if ($card === 'A') {
//             $ace += 1;
//         }
//     }

//     return $score;
// }

// //Spelaren drar ett kort
// while (calcScore($playerHand) < 21) {
//     let $drawCard = 'Vill du dra ett kort?';

//     if ($drawCard) {
//         $playerHand->draw();
//     } else {
//         break;
//     }
// }

// //Bankirern drar ett kort
// while (calcScore($bankerHand) < 21) {
//     let $drawCard = 'Vill du dra ett kort?';

//     if ($drawCard) {
//         $bankerHand->draw();
//     } else {
//         break;
//     }
// }

// //Jämför poängen för spelaren och bankiren
// let $playerScore = calcScore($playerHand);
// let $bankerScore = calcScore($bankerHand);

// if ($playerScore > 21) {
//     console.log('Spelaren förlorade!');
// } else if ($bankerScore > 21) {
//     console.log('Spelaren vann!');
// } else if ($playerScore > $bankerScore) {
//     console.log('Spelaren vann!');
// } else if ($bankerScore > $playerScore) {
//     console.log('Bankir vann!');
// } else {
//     console.log('Jämt!')
// }