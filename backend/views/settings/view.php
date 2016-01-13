<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */


?>


    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'module_id' => $module_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 'module_id' => $module_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'module_id',
            'code',
            'name',
            'value:ntext',
            'element',
            'hidden',
            'description',
        ],
    ]) ?>


