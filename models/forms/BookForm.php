<?php

declare(strict_types=1);

namespace app\models\forms;

use app\models\Author;
use app\models\Book;
use yii\base\Model;
use yii\web\UploadedFile;

class BookForm extends Model
{
    public ?int $id = null;
    public string $title = '';
    public ?int $year = null;
    public string $description = '';
    public string $isbn = '';
    public array $authorIds = [];
    public ?UploadedFile $coverFile = null;
    public string $existingCover = '';

    public function rules(): array
    {
        return [
            [['title', 'year', 'isbn', 'authorIds'], 'required'],
            [['title', 'isbn', 'description', 'existingCover'], 'string'],
            [['year', 'id'], 'integer'],
            [['year'], 'integer', 'min' => 1000, 'max' => (int) date('Y') + 1],
            [['authorIds'], 'each', 'rule' => ['integer']],
            [
                ['authorIds'],
                'each',
                'rule' => ['exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            ],
            [
                ['coverFile'],
                'file',
                'extensions' => ['png', 'jpg', 'jpeg', 'webp'],
                'mimeTypes' => ['image/png', 'image/jpeg', 'image/webp'],
                'maxSize' => 2 * 1024 * 1024,
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'authorIds' => 'Авторы',
            'coverFile' => 'Обложка',
        ];
    }

    public function loadFromBook(Book $book): void
    {
        $this->id = $book->id;
        $this->title = $book->title;
        $this->year = $book->year;
        $this->description = (string) $book->description;
        $this->isbn = $book->isbn;
        $this->existingCover = (string) $book->cover_image;
        $this->authorIds = $book->getAuthors()->select('id')->column();
    }

    public function applyToBook(Book $book): void
    {
        $book->title = $this->title;
        $book->year = (int) $this->year;
        $book->description = $this->description;
        $book->isbn = $this->isbn;
    }
}
