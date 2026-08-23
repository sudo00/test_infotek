<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Book;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class BookRepository
{
    public function findById(int $id): ?Book
    {
        return Book::find()->where(['id' => $id])->with('authors')->one();
    }

    public function getById(int $id): Book
    {
        $book = $this->findById($id);
        if ($book === null) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        return $book;
    }

    public function save(Book $book): void
    {
        if (!$book->save()) {
            throw new Exception('Не удалось сохранить книгу');
        }
    }

    public function delete(Book $book): void
    {
        $book->delete();
    }

    public function syncAuthors(Book $book, array $authorIds): void
    {
        Yii::$app->db->createCommand()
            ->delete('{{%book_author}}', ['book_id' => $book->id])
            ->execute();

        $rows = [];
        foreach (array_unique(array_map('intval', $authorIds)) as $authorId) {
            if ($authorId <= 0) {
                continue;
            }
            $rows[] = [$book->id, $authorId];
        }

        if ($rows === []) {
            return;
        }

        Yii::$app->db->createCommand()
            ->batchInsert('{{%book_author}}', ['book_id', 'author_id'], $rows)
            ->execute();
    }
}
