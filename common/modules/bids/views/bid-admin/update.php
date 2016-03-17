<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\bids\models\Bid */

$this->title = 'Update Bid: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bids', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bid-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
