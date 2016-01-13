<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\reviews\models\Reviews */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('reviews', 'Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('reviews', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('reviews', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('reviews', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'lang',
            'user_id',
            'title',
            'text:ntext',
            'photo',
            'state',
            'date',
            'date_create',
            'priority',
            'email:email',
            'notification_date',
            'notification_send',
            'order',
            'attendant_products:ntext',
            'cat_id',
            'show_in_module',
            'rate_usability',
            'rate_loyality',
            'rate_profit',
        ],
    ]) ?>

</div>
