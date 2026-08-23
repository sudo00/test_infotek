<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            'year',
            'isbn',
            [
                'label' => 'Авторы',
                'value' => static fn($model) => $model->getAuthorNames(),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => static fn() => !Yii::$app->user->isGuest,
                    'delete' => static fn() => !Yii::$app->user->isGuest,
                ],
            ],
        ],
    ]) ?>
</div>
