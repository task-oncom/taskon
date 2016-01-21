<?php
$this->tabs = array(
    'просмотреть сессию' => $this->createUrl('/testings/testingPassingAdmin/manage',array('session'=>$model->session->id)),
);

$this->page_title = 'Импорт вопросов в тест из CSV-файла';

$this->crumbs = array(
	'Список сессий' => array('/testings/testingSessionAdmin/manage'),
	'Сессия "'.$model->session->name.'"' => array('/testings/testingSessionAdmin/view','id'=>$model->session->id),
	'Список тестов' => array('/testings/testingTestAdmin/manage','session'=>$model->session->id),
	$model->name => array('/testings/testingTestAdmin/view','id'=>$model->id),
	'Импорт вопросов из CSV-Файла'

);
?>
<?php if (Yii::app()->user->hasFlash('flash')): ?>
	<?php echo $this->msg(Yii::app()->user->getFlash('flash'), 'ok'); ?>
<?php endif ?>

<?php if (isset($log)) : ?>
	<?php $this->tabs['загрузить ещё вопросов из CSV-файла'] = $this->createUrl('/testings/testingTestAdmin/importTests',array('id'=>$model->id)); ?>
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

<span style="font-size: 14px; color: #008C66;">Краткая инструкция по файла реестра вопросов и ответов в тест.</span><br /><br />
<div>
Для загрузки реестра вопросов на сайт необходимо:
<ol>
	<li>Заполнить <a href="/upload/users/Questions_example_table.xls">шаблон</a> в формате MS Excel.</li>
	<li>Сохранить файл как CSV (разделители-запятые).</li>
	<li>Загрузить файл на сайт c помощью кнопки ниже.</li>
</ol>
<span style="color: red;">Важно!</span>Не используйте клавишу ENTER для перевода строки при заполнении шаблона. Если это необходимо, пользуйтесь вместо этого тегом <strong><span style="color: red">&lt;br&gt;</span></strong>.
    <div class="message info">Внимание! Для правильной работы модуля CSV-импорта необходимо корректно заполнять шаблон. Любое отхождение от шаблона (пустая строка, добавленный столбец) может нарушить работу данной системы.</div>
<?php echo $form; ?>
