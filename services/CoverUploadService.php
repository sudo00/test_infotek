<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class CoverUploadService
{
    private const UPLOAD_DIR = '/uploads/covers';

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function save(UploadedFile $file): string
    {
        $mimeType = FileHelper::getMimeType($file->tempName, null, false);
        if ($mimeType === null || !in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new \RuntimeException('Недопустимый тип файла обложки');
        }

        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new \RuntimeException('Недопустимый тип файла обложки'),
        };

        $directory = Yii::getAlias('@webroot' . self::UPLOAD_DIR);
        FileHelper::createDirectory($directory);

        $fileName = uniqid('cover_', true) . '.' . $extension;
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;

        if (!$file->saveAs($path)) {
            throw new \RuntimeException('Не удалось сохранить файл обложки');
        }

        return self::UPLOAD_DIR . '/' . $fileName;
    }

    public function remove(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }

        if (!$this->isAllowedCoverPath($relativePath)) {
            Yii::warning('Refused to remove unexpected cover path: ' . $relativePath, __METHOD__);

            return;
        }

        $fullPath = Yii::getAlias('@webroot') . $relativePath;
        $realPath = realpath($fullPath);
        $uploadRoot = realpath(Yii::getAlias('@webroot' . self::UPLOAD_DIR));

        if ($realPath === false || $uploadRoot === false || !str_starts_with($realPath, $uploadRoot . DIRECTORY_SEPARATOR)) {
            return;
        }

        if (is_file($realPath)) {
            unlink($realPath);
        }
    }

    private function isAllowedCoverPath(string $relativePath): bool
    {
        if (!str_starts_with($relativePath, self::UPLOAD_DIR . '/')) {
            return false;
        }

        $fileName = basename($relativePath);

        return $fileName !== '' && $fileName !== '.' && $fileName !== '..' && !str_contains($fileName, '..');
    }
}
