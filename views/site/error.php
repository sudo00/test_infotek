<?php

use yii\helpers\Html;
use yii\web\HttpException;

$this->title = $name;
$statusCode = $exception instanceof HttpException ? $exception->statusCode : 500;
?>
<div class="site-error">
    <h1><?= Html::encode((string) $statusCode) ?></h1>
    <p><?= nl2br(Html::encode($message)) ?></p>
    <p><?= Html::a('На главную', Yii::$app->homeUrl, ['class' => 'btn btn-primary']) ?></p>
</div>
