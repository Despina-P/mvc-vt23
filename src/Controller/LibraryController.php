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

    #[Route('/library/show/{id}', name: 'book_by_id')]
    public function showBookById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository
            ->find($id);

        return $this->render('library/show-one.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'book_delete_by_id', methods: ['POST'])]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        //$entityManager = $doctrine->getManager();
        //$product = $entityManager->getRepository(Product::class)->find($id);
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        //$entityManager->remove($product);
        //$entityManager->flush();
        $libraryRepository->remove($book, true);

        return $this->redirectToRoute('library_show_all');
    }

    #[Route('/library/update-form', name: 'book_update_form', methods: ['GET'])]
    public function updateBookForm(
        LibraryRepository $libraryRepository,
        Request $request
    ): Response {
        $id = $request->query->get('id');
        $isbn = $request->query->get('isbn');

        $book = $libraryRepository->find($id);

        return $this->render('library/update-form.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/update/{id}/{isbn}', name: 'book_update', methods: ['POST'])]
    public function updateBook(
        LibraryRepository $libraryRepository,
        Request $request,
        int $id,
        int $isbn
    ): Response {
        // $entityManager = $doctrine->getManager();
        // $book = $entityManager->getRepository(Library::class)->find($id);
        $book = $libraryRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
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

        //$entityManager->flush();
        $libraryRepository->save($book, true);

        return $this->redirectToRoute('library_show_all', [
            'book' => $book,
        ]);
    }

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
