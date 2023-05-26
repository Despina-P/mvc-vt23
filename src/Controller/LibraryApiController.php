<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryApiController extends AbstractController
{
    #[Route('/api/library/books', name: 'api_show_all')]
    public function showAllBooksJSON(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route('/api/library/book/{isbn<\d+>}', name: 'api_show_one')]
    public function showBookByIdJSON(
        LibraryRepository $libraryRepository,
        int $isbn
    ): Response {
        $book = $libraryRepository
            ->findByISBN($isbn);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
