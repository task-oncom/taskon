<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\FaqCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Faq Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id, 'name' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('content', 'Delete'), ['delete', 'id' => $model->id, 'name' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('content', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
