<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php if (\Yii::$app->session->hasFlash('flash')): ?>
	<div class="alert alert-success fade in m-b-15">
		<?php echo \Yii::$app->session->getFlash('flash'); ?>
		<span class="close" data-dismiss="alert">×</span>
	</div>
<?php endif ?>

<?php if (isset($log)) : ?>
	
	<div id="log_box"><?php echo $log; ?></div> 

	<hr>

	<script type="text/javascript">
		jQuery('#log_box p').each(function(){
			var str = $(this).text();
			var reg = /Ошибка/gi;
			if (str.match(reg)) {
				$(this).css('color','red');
			}
		});
	</script>
<?php endif; ?>

<span style="font-size: 14px; color: #008C66;">Краткая инструкция по файла назначения теста пользователям</span><br /><br />
<div>
Для загрузки реестра пользователей на сайт необходимо:
<ol>
	<li>Заполнить <a href="/uploads/templates/Users_example_table.xlsx">шаблон</a> в формате MS Excel. Поля для назначения тестов имеют формат "да/нет".</li>
	<li>ВАЖНО! Список тестов в сессии необходимо создать заранее! Порядок создания тестов в сессии определяет порядок столбцов в файле!</li>
	<li>Сохранить файл как XLS.</li>
	<li>Загрузить файл на сайт c помощью кнопки ниже.</li>
</ol>
<span style="color: red;">Важно!</span> Не используйте клавишу ENTER для перевода строки при заполнении шаблона. Если это необходимо, пользуйтесь вместо этого тегом <strong><span style="color: red">&lt;br&gt;</span></strong>.
    
<div class="message info">Внимание! Для правильной работы модуля CSV-импорта необходимо корректно заполнять шаблон. Любое отхождение от шаблона (пустая строка, добавленный столбец) может нарушить работу данной системы.</div>

<hr>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($group, 'name')->textInput() ?>

    <?= $form->field($model, 'csv_file')->fileInput() ?>

    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end() ?>
