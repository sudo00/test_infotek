<?php

declare(strict_types=1);

namespace app\repositories;

use app\models\Author;
use yii\web\NotFoundHttpException;

class AuthorRepository
{
    public function findAllOrderedByName(): array
    {
        return Author::find()->orderBy(['full_name' => SORT_ASC])->all();
    }

    public function findById(int $id): ?Author
    {
        return Author::find()->where(['id' => $id])->with('books')->one();
    }

    public function getById(int $id): Author
    {
        $author = $this->findById($id);
        if ($author === null) {
            throw new NotFoundHttpException('Автор не найден');
        }

        return $author;
    }

    public function save(Author $author): bool
    {
        return $author->save();
    }

    public function delete(Author $author): void
    {
        $author->delete();
    }
}
