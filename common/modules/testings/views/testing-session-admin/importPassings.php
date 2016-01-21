<?php
$this->tabs = array(
    'просмотреть сессию' => $this->createUrl('/testings/testingPassingAdmin/manage',array('session'=>$model->id)),
);

$this->page_title = 'Импорт пользователей из CSV-файла';

$this->crumbs = array(
	'Список сессий' => array('/testings/testingSessionAdmin/manage'),
	$model->name => array('/testings/testingPassingAdmin/manage','session'=>$model->id),
	'Импорт пользователей из CSV-файла',
);
?>

<?php if (Yii::app()->user->hasFlash('flash')): ?>
	<?php echo $this->msg(Yii::app()->user->getFlash('flash'), 'ok'); ?>
<?php endif ?>

<?php if (isset($log)) : ?>
	<?php $this->tabs['загрузить ещё пользователей из CSV-файла'] = $this->createUrl('/testings/testingSessionAdmin/importCSV',array('id'=>$model->id)); ?>
	<div><?php echo $log; ?></div>

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

<span style="font-size: 14px; color: #008C66;">Краткая инструкция по файла назначения теста пользователям</span><br /><br />
<div>
Для загрузки реестра пользователей на сайт необходимо:
<ol>
	<li>Заполнить <a href="/upload/users/Users_example_table.xls">шаблон</a> в формате MS Excel. Поля для назначения тестов имеют формат "да/нет".</li>
	<li>ВАЖНО! Список тестов в сессии необходимо создать заранее! Порядок создания тестов в сессии определяет порядок столбцов в файле!</li>
	<li>Сохранить файл как CSV (разделители-запятые).</li>
	<li>Загрузить файл на сайт c помощью кнопки ниже.</li>
</ol>
<span style="color: red;">Важно!</span> Не используйте клавишу ENTER для перевода строки при заполнении шаблона. Если это необходимо, пользуйтесь вместо этого тегом <strong><span style="color: red">&lt;br&gt;</span></strong>.
    <div class="message info">Внимание! Для правильной работы модуля CSV-импорта необходимо корректно заполнять шаблон. Любое отхождение от шаблона (пустая строка, добавленный столбец) может нарушить работу данной системы.</div>

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'import-csv-form',
    'htmlOptions' => array(
    	'enctype' => 'multipart/form-data'
    )
)); ?>

	<dl class="text">
		<dd>
			<?=$form->label($group, 'name')?>
		    <?=$form->textField($group, 'name', array(
		    	'class' => 'text'
		    ));?>
		    <?=$form->error($group, 'name');?>
		</dd>
	</dl>

	<dl class="file">
		<dd>
			<?=$form->label($model, 'csv_file')?>
		    <?=$form->fileField($model, 'csv_file');?>
		    <?=$form->error($model, 'csv_file');?>
		</dd>
	</dl>
	
	<div class="row buttons">
		<?=CHtml::submitButton('Загрузить', array(
			'class' => 'submit mid'
		))?>
	</div>

<?php $this->endWidget();  ?>