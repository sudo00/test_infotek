<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <div>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Удалить книгу?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        <?php endif ?>
    </div>

    <?php if ($model->cover_image): ?>
        <p><?= Html::img($model->cover_image, ['class' => 'img-thumbnail mb-3', 'style' => 'max-height: 300px']) ?></p>
    <?php endif ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'isbn',
            'description:ntext',
            [
                'label' => 'Авторы',
                'value' => $model->getAuthorNames(),
            ],
        ],
    ]) ?>
</div>
