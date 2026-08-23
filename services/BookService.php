<?php

declare(strict_types=1);

namespace app\services;

use app\models\Book;
use app\models\forms\BookForm;
use app\models\search\BookSearch;
use app\repositories\AuthorRepository;
use app\repositories\BookRepository;
use Yii;

class BookService
{
    public function __construct(
        private readonly BookRepository $books,
        private readonly AuthorRepository $authors,
        private readonly CoverUploadService $covers,
        private readonly BookNotificationService $notifications,
    ) {
    }

    public function getList(array $queryParams): array
    {
        $searchModel = new BookSearch();

        return [
            'searchModel' => $searchModel,
            'dataProvider' => $searchModel->search($queryParams),
        ];
    }

    public function getById(int $id): Book
    {
        return $this->books->getById($id);
    }

    public function getCreateFormData(): array
    {
        return [
            'form' => new BookForm(),
            'authors' => $this->authors->findAllOrderedByName(),
        ];
    }

    public function getUpdateFormData(int $id): array
    {
        $book = $this->books->getById($id);
        $form = new BookForm();
        $form->loadFromBook($book);

        return [
            'form' => $form,
            'authors' => $this->authors->findAllOrderedByName(),
        ];
    }

    public function create(BookForm $form): ?Book
    {
        if (!$form->validate()) {
            return null;
        }

        $book = new Book();
        $form->applyToBook($book);

        $coverPath = null;
        if ($form->coverFile !== null) {
            $coverPath = $this->covers->save($form->coverFile);
            $book->cover_image = $coverPath;
        }

        if (!$book->validate()) {
            $this->copyModelErrorsToForm($book, $form);
            if ($coverPath !== null) {
                $this->covers->remove($coverPath);
            }

            return null;
        }

        try {
            $this->persist($book, $form->authorIds);
        } catch (\Throwable $e) {
            if ($coverPath !== null) {
                $this->covers->remove($coverPath);
            }
            throw $e;
        }

        $smsOk = $this->notifications->notifyAboutNewBook($book, $form->authorIds);
        if (!$smsOk) {
            Yii::$app->session->setFlash(
                'warning',
                'Книга сохранена, но часть SMS-уведомлений не удалось отправить.',
            );
        }

        return $book;
    }

    public function update(int $id, BookForm $form): ?Book
    {
        if (!$form->validate()) {
            return null;
        }

        $book = $this->books->getById($id);
        $form->applyToBook($book);

        $oldCover = $book->cover_image;
        $newCoverPath = null;

        if ($form->coverFile !== null) {
            $newCoverPath = $this->covers->save($form->coverFile);
            $book->cover_image = $newCoverPath;
        }

        if (!$book->validate()) {
            $this->copyModelErrorsToForm($book, $form);
            if ($newCoverPath !== null) {
                $this->covers->remove($newCoverPath);
            }

            return null;
        }

        try {
            $this->persist($book, $form->authorIds);
        } catch (\Throwable $e) {
            if ($newCoverPath !== null) {
                $this->covers->remove($newCoverPath);
            }
            throw $e;
        }

        if ($newCoverPath !== null) {
            $this->covers->remove($oldCover);
        }

        return $book;
    }

    public function delete(int $id): void
    {
        $book = $this->books->getById($id);
        $cover = $book->cover_image;
        $this->books->delete($book);
        $this->covers->remove($cover);
    }

    private function persist(Book $book, array $authorIds): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->books->save($book);
            $this->books->syncAuthors($book, $authorIds);
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    private function copyModelErrorsToForm(Book $book, BookForm $form): void
    {
        foreach ($book->getErrors() as $attribute => $errors) {
            foreach ($errors as $error) {
                $form->addError($attribute, $error);
            }
        }
    }
}
