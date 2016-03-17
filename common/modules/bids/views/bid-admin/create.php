<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\bids\models\Bid */

$this->title = 'Create Bid';
$this->params['breadcrumbs'][] = ['label' => 'Bids', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
