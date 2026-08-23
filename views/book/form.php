<?php

use app\models\Author;
use app\models\forms\BookForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = $form->id ? 'Редактирование книги' : 'Новая книга';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $activeForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $activeForm->field($form, 'title')->textInput(['maxlength' => true]) ?>
    <?= $activeForm->field($form, 'year')->input('number') ?>
    <?= $activeForm->field($form, 'isbn')->textInput(['maxlength' => true]) ?>
    <?= $activeForm->field($form, 'description')->textarea(['rows' => 6]) ?>
    <?= $activeForm->field($form, 'authorIds')->checkboxList(
        ArrayHelper::map($authors, 'id', 'full_name'),
    ) ?>
    <?= $activeForm->field($form, 'coverFile')->fileInput() ?>

    <?php if ($form->existingCover): ?>
        <p>Текущая обложка: <?= Html::img($form->existingCover, ['class' => 'img-thumbnail', 'style' => 'max-height: 120px']) ?></p>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end() ?>
</div>
