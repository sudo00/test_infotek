<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Author;
use app\services\AuthorService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AuthorController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly AuthorService $authorService,
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
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index', [
            'authors' => $this->authorService->getList(),
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->authorService->getById($id),
        ]);
    }

    public function actionCreate(): Response|string
    {
        $model = new Author();

        if ($model->load(Yii::$app->request->post()) && $this->authorService->create($model)) {
            Yii::$app->session->setFlash('success', 'Автор добавлен');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('form', ['model' => $model]);
    }

    public function actionUpdate(int $id): Response|string
    {
        $model = $this->authorService->getById($id);

        if ($model->load(Yii::$app->request->post()) && $this->authorService->update($model)) {
            Yii::$app->session->setFlash('success', 'Автор обновлён');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('form', ['model' => $model]);
    }

    public function actionDelete(int $id): Response
    {
        $this->authorService->delete($id);
        Yii::$app->session->setFlash('success', 'Автор удалён');

        return $this->redirect(['index']);
    }
}
