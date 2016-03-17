<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\languages\models\Languages */

$this->title = Yii::t('content', 'Update {modelClass}: ', [
    'modelClass' => 'Languages',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('content', 'Update');
?>
<div class="languages-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
