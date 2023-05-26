<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllBooks(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();

        return $this->render('library/show-all.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/show/{idnumber}', name: 'book_by_id')]
    public function showBookById(
        LibraryRepository $libraryRepository,
        int $idnumber
    ): Response {
        $book = $libraryRepository
            ->find($idnumber);

        return $this->render('library/show-one.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{idnumber}', name: 'book_delete_by_id', methods: ['POST'])]
    public function deleteBookById(
        LibraryRepository $libraryRepository,
        int $idnumber
    ): Response {
        $book = $libraryRepository->find($idnumber);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$idnumber
            );
        }

        $libraryRepository->remove($book, true);

        return $this->redirectToRoute('library_show_all');
    }
}
