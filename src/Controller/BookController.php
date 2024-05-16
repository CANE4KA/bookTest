<?php

namespace App\Controller;

use App\Exception\BookNotFoundException;
use App\Exception\DuplicateException;
use App\Exception\ValidationFailedException;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    #[Route('/books', name: 'books', methods: 'GET')]
    public function getAllBook(): JsonResponse
    {
        return new JsonResponse($this->bookService->getAllBooks());
    }

    /**
     * @throws DuplicateException
     * @throws ValidationFailedException
     */
    #[Route('/book', name: 'addBook', methods: 'POST')]
    public function addBook(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['name']) || empty($data['author']) || empty($data['year'])) {
            throw new ValidationFailedException();
        }

        $this->bookService->addBook(
            $data['name'],
            $data['author'],
            $data['year']
        );

        return new JsonResponse('Success');
    }

    /**
     * @throws BookNotFoundException
     * @throws ValidationFailedException
     */
    #[Route('/book/{id}', name: 'updateBook', methods: 'PUT')]
    public function updateBook(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['name']) || empty($data['author']) || empty($data['year'])) {
            throw new ValidationFailedException();
        }

        $this->bookService->updateBook(
            $id,
            $data['name'],
            $data['author'],
            $data["year"]
        );

        return new JsonResponse('Success');
    }

    /**
     * @throws BookNotFoundException
     */
    #[Route('/book/{id}', name: 'deleteBook', methods: 'DELETE')]
    public function deleteBook(int $id): JsonResponse
    {
        $this->bookService->deleteBook($id);

        return new JsonResponse('Success');
    }
}