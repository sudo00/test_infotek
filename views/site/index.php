<?php

use yii\helpers\Html;

$this->title = 'Каталог книг';
?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Книги', ['/book/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Авторы', ['/author/index'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('ТОП-10 авторов', ['/report/top-authors'], ['class' => 'btn btn-outline-secondary']) ?>
    </p>
    <p>Логин: <code>user</code> / <code>user123</code></p>
</div>
