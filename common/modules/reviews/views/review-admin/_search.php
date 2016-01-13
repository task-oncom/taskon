<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\reviews\models\SearchReviews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reviews-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lang') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'notification_date') ?>

    <?php // echo $form->field($model, 'notification_send') ?>

    <?php // echo $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'attendant_products') ?>

    <?php // echo $form->field($model, 'cat_id') ?>

    <?php // echo $form->field($model, 'show_in_module') ?>

    <?php // echo $form->field($model, 'rate_usability') ?>

    <?php // echo $form->field($model, 'rate_loyality') ?>

    <?php // echo $form->field($model, 'rate_profit') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('reviews', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('reviews', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
