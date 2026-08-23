<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\BookService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class BookController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly BookService $bookService,
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
        return $this->render('index', $this->bookService->getList(Yii::$app->request->queryParams));
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->bookService->getById($id),
        ]);
    }

    public function actionCreate(): Response|string
    {
        $data = $this->bookService->getCreateFormData();
        $form = $data['form'];

        if ($form->load(Yii::$app->request->post())) {
            $form->coverFile = UploadedFile::getInstance($form, 'coverFile');
            $book = $this->bookService->create($form);

            if ($book !== null) {
                Yii::$app->session->setFlash('success', 'Книга добавлена');

                return $this->redirect(['view', 'id' => $book->id]);
            }
        }

        return $this->render('form', $data);
    }

    public function actionUpdate(int $id): Response|string
    {
        $data = $this->bookService->getUpdateFormData($id);
        $form = $data['form'];

        if ($form->load(Yii::$app->request->post())) {
            $form->coverFile = UploadedFile::getInstance($form, 'coverFile');
            $book = $this->bookService->update($id, $form);

            if ($book !== null) {
                Yii::$app->session->setFlash('success', 'Книга обновлена');

                return $this->redirect(['view', 'id' => $book->id]);
            }
        }

        return $this->render('form', $data);
    }

    public function actionDelete(int $id): Response
    {
        $this->bookService->delete($id);
        Yii::$app->session->setFlash('success', 'Книга удалена');

        return $this->redirect(['index']);
    }
}
