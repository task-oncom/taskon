<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\languages\models\Languages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="languages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => 2]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 15]) ?>

    <?= $form->field($model, 'default')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
