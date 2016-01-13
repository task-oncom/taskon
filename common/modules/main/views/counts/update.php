<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\Counts */

$this->title = 'Update Counts: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Counts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="counts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
