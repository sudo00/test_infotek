<?php

declare(strict_types=1);

namespace app\services;

use app\repositories\ReportRepository;

class ReportService
{
    public function __construct(
        private readonly ReportRepository $reports,
    ) {
    }

    public function getTopAuthors(?int $year = null, int $limit = 10): array
    {
        $currentYear = (int) date('Y');
        $minYear = 1000;
        $maxYear = $currentYear + 1;

        $year = $year ?? $currentYear;
        if ($year < $minYear || $year > $maxYear) {
            $year = $currentYear;
        }

        return [
            'year' => $year,
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'rows' => $this->reports->getTopAuthorsByYear($year, $limit),
        ];
    }
}
