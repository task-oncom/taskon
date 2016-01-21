<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php if (\Yii::$app->session->hasFlash('flash')): ?>
	<div class="alert alert-success fade in m-b-15">
		<?php echo $this->msg(\Yii::$app->session->getFlash('flash'), 'ok'); ?>
		<span class="close" data-dismiss="alert">×</span>
	</div>
<?php endif ?>

<?php if (isset($log)) : ?>
	
	<div id="log_box"><?php echo $log; ?></div>

	<script type="text/javascript">
		jQuery('#log_box p').each(function(){
			var str = $(this).text();
			var reg = /Ошибка/gi;
			if (str.match(reg)) {
				$(this).css('background-color','#ccc').css('color','red');
			}
		});
	</script>
<?php endif; ?>

<span style="font-size: 14px; color: #008C66;">Краткая инструкция по файла реестра вопросов и ответов в тест.</span>

<br /><br />

Для загрузки реестра вопросов на сайт необходимо:

<ol>
	<li>Заполнить <a href="/upload/users/Questions_example_table.xls">шаблон</a> в формате MS Excel.</li>
	<li>Сохранить файл как XLS или XLSX.</li>
	<li>Загрузить файл на сайт c помощью кнопки ниже.</li>
</ol>

<span style="color: red;">Важно!</span> Не используйте клавишу ENTER для перевода строки при заполнении шаблона. Если это необходимо, пользуйтесь вместо этого тегом <strong><span style="color: red">&lt;br&gt;</span></strong>.

<br /><br />

<div class="alert alert-info">Внимание! Для правильной работы модуля XLS-импорта необходимо корректно заполнять шаблон. Любое отхождение от шаблона (пустая строка, добавленный столбец) может нарушить работу данной системы.</div>

<hr>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'csv_file')->fileInput() ?>

    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']); ?>

<?php ActiveForm::end() ?>
