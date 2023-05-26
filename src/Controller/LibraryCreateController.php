<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LibraryCreateController extends AbstractController
{
    #[Route('/library/create', name: 'library_create', methods: ['POST'])]
    public function createLibrary(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Library();
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

        $entityManager->persist($book);
        $entityManager->flush();

        return new Response('Saved new product with id '.$book->getId());
    }

    #[Route('library/create-form', name:'library_create_form', methods: ['GET'])]
    public function createFormLibrary(): Response
    {
        return $this->render('library/create-form.html.twig');
    }
}