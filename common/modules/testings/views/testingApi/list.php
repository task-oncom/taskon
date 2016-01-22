<h2>Имеющиеся списки в системе unisender (при необходимости лишнее можно удалить)</h2>
<?php if(Yii::app()->user->hasFlash('list_generate')): ?>
	
	<div class="l-system_message">
		<hr/>
			<p><b><?php echo Yii::app()->user->getFlash('list_generate'); ?></b></p>
		<hr/>
	</div>
	
<?php endif; ?>

<?php
$api = new UniSenderApi;
$result = $api->__call('getLists', array());
// Раскодируем ответ API-сервера
$data = $api->checkErrors($result);
if($data['status'] == true) {
	foreach ($data['data'] -> result as $one) {
		echo '<div class = "d-list">';
			echo "<p>Список #" . $one->id . " (" . $one->title . ")". " ";
			echo '<a href = "'.Yii::app()->createUrl('/testings/TestingApi/deleteList', array('id'=>$one->id)).'">Удалить</a> </p>';
		echo '</div>';
    }
}

?>

<div class = "d-list_responce"></div>
	
<h2>Создать новый список для рассылки</h2>

<div class = "form">
	<?php echo CHtml::beginForm(); ?>

	<dl class="text">
		<dd>
			<?php echo CHtml::label('Введите название группы', 'unisender_new_list'); ?>
			<?php echo CHtml::textField("unisender_new_list", '', array('class'=>'text')); ?>
		</dd>
	</dl>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Создать', array('class'=>'submit mid')); ?>
	</div>
	 
	<?php echo CHtml::endForm(); ?>
</div>

<script type = "text/javascript">
	$('.d-list a').click(function() {
		var url = $(this).attr('href');
		var link = $(this);
		if(confirm("Вы уверены, что хотите удалить данный список?")){
			$.ajax({
				'url': url,
				'type': "POST",
				'success':function(data) {
					link.parents('.d-list').remove();
					$('.d-list_responce').html(data);
				},
				'error':function(data) {
					$('.d-list_responce').html(data);
				}
			})
		}
		return false;
	})
</script>