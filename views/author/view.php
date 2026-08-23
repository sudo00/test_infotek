<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Подписаться на новые книги', ['subscription/create', 'author_id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php else: ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => ['confirm' => 'Удалить автора?', 'method' => 'post'],
                ]) ?>
            <?php endif ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            [
                'label' => 'Книги',
                'format' => 'raw',
                'value' => static function ($model) {
                    $links = [];
                    foreach ($model->books as $book) {
                        $links[] = Html::a(Html::encode($book->title), ['book/view', 'id' => $book->id]);
                    }
                    return $links ? implode('<br>', $links) : '—';
                },
            ],
        ],
    ]) ?>
</div>
