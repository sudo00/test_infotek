<?php

use yii\helpers\Html;

/** @var int $year */
/** @var int $minYear */
/** @var int $maxYear */
/** @var array $rows */

$this->title = 'ТОП-10 авторов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm(['top-authors'], 'get', ['class' => 'row g-3 align-items-end mb-4']) ?>
    <div class="col-auto">
        <label class="form-label" for="year">Год</label>
        <input
            type="number"
            id="year"
            name="year"
            class="form-control"
            min="<?= (int) $minYear ?>"
            max="<?= (int) $maxYear ?>"
            value="<?= Html::encode((string) $year) ?>"
        >
    </div>
    <div class="col-auto">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
    </div>
    <?= Html::endForm() ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Автор</th>
            <th>Книг за <?= Html::encode((string) $year) ?> г.</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($rows === []): ?>
            <tr><td colspan="3">Нет данных за выбранный год</td></tr>
        <?php else: ?>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::a(Html::encode($row['full_name']), ['author/view', 'id' => $row['id']]) ?></td>
                    <td><?= Html::encode((string) $row['books_count']) ?></td>
                </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
</div>
