<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\SubscriptionService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SubscriptionController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly SubscriptionService $subscriptionService,
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
                        'actions' => ['create'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['create' => ['GET', 'POST']],
            ],
        ];
    }

    public function actionCreate(int $author_id): Response|string
    {
        $author = $this->subscriptionService->getAuthor($author_id);
        $model = $this->subscriptionService->createForm($author->id);

        if ($model->load(Yii::$app->request->post())) {
            // author_id берём только из URL, не из POST
            $model->author_id = $author->id;

            if ($this->subscriptionService->subscribe($model)) {
                Yii::$app->session->setFlash(
                    'success',
                    'Подписка оформлена. SMS-уведомление придёт при выходе новой книги автора.',
                );

                return $this->redirect(['author/view', 'id' => $author->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'author' => $author,
        ]);
    }
}
