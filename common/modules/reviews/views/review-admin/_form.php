<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\reviews\models\Reviews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reviews-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lang')->textInput(['maxlength' => 2]) ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 250]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'photo')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'state')->dropDownList([ 'active' => 'Active', 'hidden' => 'Hidden', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'date_create')->textInput() ?>

    <?= $form->field($model, 'priority')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'notification_date')->textInput() ?>

    <?= $form->field($model, 'notification_send')->textInput() ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'attendant_products')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cat_id')->textInput() ?>

    <?= $form->field($model, 'show_in_module')->textInput() ?>

    <?= $form->field($model, 'rate_usability')->textInput() ?>

    <?= $form->field($model, 'rate_loyality')->textInput() ?>

    <?= $form->field($model, 'rate_profit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('reviews', 'Create') : Yii::t('reviews', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
