<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'library_create')]
    public function createBook(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = new Book();

        // skapar formulär enligt https://symfony.com/doc/current/forms.html
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('library_view_all');
        }

        return $this->render('library/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/library/view', name: 'library_view_all')]
    public function viewAllBooks(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('library/view.html.twig', $data);
    }

    #[Route('/library/view/{id}', name: 'library_view_one')]
    public function viewOneBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('library/view_one.html.twig', $data);
    }

    #[Route('/library/update/{id}', name: 'library_update')]
    public function updateBook(
        BookRepository $bookRepository,
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('library_view_one', ['id' => $book->getId()]);
        }

        return $this->render('library/update.html.twig', [
            'form' => $form,
            'book' => $book
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function deleteBook(
        BookRepository $bookRepository,
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_view_all');
    }
}
