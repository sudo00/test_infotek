<?php

declare(strict_types=1);

namespace app\repositories;

use Yii;

class ReportRepository
{
    public function getTopAuthorsByYear(int $year, int $limit = 10): array
    {
        $limit = max(1, min(100, $limit));

        return Yii::$app->db->createCommand(
            <<<SQL
            SELECT a.id, a.full_name, COUNT(b.id) AS books_count
            FROM {{%author}} a
            INNER JOIN {{%book_author}} ba ON ba.author_id = a.id
            INNER JOIN {{%book}} b ON b.id = ba.book_id AND b.year = :year
            GROUP BY a.id, a.full_name
            ORDER BY books_count DESC, a.full_name ASC
            LIMIT {$limit}
            SQL,
            [':year' => $year],
        )->queryAll();
    }
}
