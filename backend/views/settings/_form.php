<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Settings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'module_id')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'element')->dropDownList([ 'text' => 'Text', 'textarea' => 'Textarea', 'editor' => 'Editor', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'hidden')->textInput() ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 2550]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
