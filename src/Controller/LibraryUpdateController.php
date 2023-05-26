<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LibraryUpdateController extends AbstractController
{

    #[Route('/library/update-form', name: 'book_update_form', methods: ['GET'])]
    public function updateBookForm(
        LibraryRepository $libraryRepository,
        Request $request
    ): Response {
        $idnumber = $request->query->get('idnumber');

        $book = $libraryRepository->find($idnumber);

        return $this->render('library/update-form.html.twig', [
            'book' => $book,
            'idnumber' => $idnumber
        ]);
    }

    #[Route('/library/update/{idnumber}/{isbn}', name: 'book_update', methods: ['POST'])]
    public function updateBook(
        LibraryRepository $libraryRepository,
        Request $request,
        int $idnumber,
        int $isbn
    ): Response {
        $book = $libraryRepository->find($idnumber);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$idnumber
            );
        }

        $title = $request->request->get('title');
        if (isset($title)) {
            $book->setTitle(strval($title));
        }
        
        $author = $request->request->get('author');
        if (isset($author)) {
            $book->setAuthor(strval($author));
        }
        
        $isbn = $request->request->get('isbn');
        if (isset($isbn)) {
            $book->setISBN(intval($isbn));
        }
        
        $img = $request->request->get('img');
        if (isset($img)) {
            $book->setImg(strval($img));
        }

        $libraryRepository->save($book, true);

        return $this->redirectToRoute('library_show_all', [
            'book' => $book,
        ]);
    }
}