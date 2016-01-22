<?php
Yii::app()->clientScript->registerScriptFile(
    Yii::app()->controller->module->assetsUrl() . '/js/AnswerSubForm.js'
);
?>

<fieldset>
    <legend>Ответы:</legend>
    <div id="subform_elements"></div>
    <a href="#" class="action_link" id="add_answer_link">добавить вариант ответа</a>
</fieldset>

