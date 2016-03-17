<?php

use yii\helpers\Html;

echo Html::tag('h4', 'Meta-теги страницы:');
        
echo $form->field($model, '['.$model->lang_id.']title')->textInput();
echo $form->field($model, '['.$model->lang_id.']description')->textInput();
echo $form->field($model, '['.$model->lang_id.']keywords')->textInput();