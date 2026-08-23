<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\ReportService;
use yii\filters\AccessControl;
use yii\web\Controller;

class ReportController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly ReportService $reportService,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['top-authors'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionTopAuthors(?int $year = null): string
    {
        return $this->render('top-authors', $this->reportService->getTopAuthors($year));
    }
}
