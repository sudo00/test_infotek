<?php

declare(strict_types=1);

namespace app\services;

use app\models\Author;
use app\repositories\AuthorRepository;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $authors,
    ) {
    }

    public function getList(): array
    {
        return $this->authors->findAllOrderedByName();
    }

    public function getById(int $id): Author
    {
        return $this->authors->getById($id);
    }

    public function create(Author $author): bool
    {
        return $this->authors->save($author);
    }

    public function update(Author $author): bool
    {
        return $this->authors->save($author);
    }

    public function delete(int $id): void
    {
        $author = $this->authors->getById($id);
        $this->authors->delete($author);
    }
}
