<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\PostTag */

$this->title = 'Update Post Tag: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Post Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-tag-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
