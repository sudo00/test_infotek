<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Подписка на автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['author/index']];
$this->params['breadcrumbs'][] = ['label' => $author->full_name, 'url' => ['author/view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Автор: <strong><?= Html::encode($author->full_name) ?></strong></p>

    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'author_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+79991234567']) ?>
    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>
