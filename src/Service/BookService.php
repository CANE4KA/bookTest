<?php

namespace App\Service;

use App\Entity\Books;
use App\Exception\BookNotFoundException;
use App\Exception\DuplicateException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BookService
{
    private readonly EntityRepository $bookRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->bookRepository = $this->entityManager->getRepository(Books::class);
    }

    public function getAllBooks(): array
    {
        $books = $this->bookRepository->findAll();

        $data = [];
        /** @var Books $generation */
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'author' => $book->getAuthor(),
                'year' => $book->getYear(),
            ];
        }

        return $data;
    }

    /**
     * @throws DuplicateException
     */
    public function addBook(string $name, string $author, int $year): void
    {
        $book = $this->bookRepository->findOneBy(["name" => $name]);
        if (null !== $book) {
            throw new DuplicateException();
        }

        $book = (new Books())
            ->setName($name)
            ->setAuthor($author)
            ->setYear($year);

        $this->entityManager->persist($book);
        $this->entityManager->flush();
    }

    /**
     * @throws BookNotFoundException
     */
    public function updateBook(int $id, string $name, string $author, int $year): void
    {
        $book = $this->bookRepository->find($id);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        $book
            ->setName($name)
            ->setAuthor($author)
            ->setYear($year);

        $this->entityManager->flush();
    }

    /**
     * @throws BookNotFoundException
     */
    public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->find($id);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }
}