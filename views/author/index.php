<?php

use yii\helpers\Html;


$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif ?>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>ФИО</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($authors as $author): ?>
            <tr>
                <td><?= Html::encode($author->id) ?></td>
                <td><?= Html::a(Html::encode($author->full_name), ['view', 'id' => $author->id]) ?></td>
                <td class="text-end">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('Подписаться', ['subscription/create', 'author_id' => $author->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    <?php else: ?>
                        <?= Html::a('Редактировать', ['update', 'id' => $author->id], ['class' => 'btn btn-sm btn-primary']) ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
