<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\FaqCategory */

$this->title = Yii::t('content', 'Update {modelClass}: ', [
    'modelClass' => 'Faq Category',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Faq Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id, 'name' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('content', 'Update');
?>
<div class="faq-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
