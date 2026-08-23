<?php

declare(strict_types=1);


use yii\helpers\Html;

?>
<footer id="footer" class="mt-auto py-3 bg-body-tertiary">
    <div class="container text-center text-body-secondary">
        &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
    </div>
</footer>
