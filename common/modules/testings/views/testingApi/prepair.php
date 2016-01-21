<h2>Загрузка подписчиков в Unisender</h2>
<p>Выберите группу, в которую будут загружаться подписчики</p>

<?php
$api = new UniSenderApi;
$result = $api->__call('getLists', array());
$data = $api->checkErrors($result);
$group = array();
if($data['status'] == true) {
	foreach ($data['data'] -> result as $one) {
		$group[$one->id] = $one->title;
    }
}
echo CHtml::dropDownList('group_list', 	'', $group, array('class'=>'j-group_list'));
?>
<br/>
<br/>
<p>Выберите сессию, из которой будет происходить выгрузка</p>
<?php 
$testing = TestingSession::model()->findAll(array('order'=>'id DESC'));
$lastTest = TestingSession::model()->find(array('order'=>'id DESC'));
foreach($testing as $test) {
	$t[$test->id] = $test->name;
}

echo CHtml::dropDownList('session_list', '', $t, array('class'=>'j-session_list'));

?>
<br/>
<p>Общее число уникальных пользователей для рассылки: <span class = "d-persons"><?=count($this->getUnique($lastTest->id));?></span></p>
<p>
Выгружать по <input type = "text" name = "limit" class = "j-limit text" value = "300"/> пользователей<br/><br/> 
начиная с <input type = "text" name = "limit" class = "j-start text" value = "1"/> пользователя 
</p>

<a href = "<?=Yii::app()->createUrl('testings/TestingApi/send');?>" class = "a-send_data">Начать выгрузку</a>

<div class = "d-upload_status"></div>

<script type = "text/javascript">
	$('.j-session_list').change(function() {
		var url = "<?=Yii::app()->createUrl('/testings/TestingApi/GetUnique');?>";
		var val = $(this).val();
		$.ajax({
			'url': url,
			'type': "POST",
			data: { sessionId: val},
			'success':function(data) {
				$('.d-persons').html(data);
			},
			'error':function(data) {
				$('.d-persons').html(data);
			}
		})
		return false;
	});
	
	$('.a-send_data').click(function() {
		var url = $(this).attr('href');
		var sessionId = $('.j-session_list').val();
		var groupId = $('.j-group_list').val();
		var limit = $('.j-limit').val();
		var start = $('.j-start').val();
		$.ajax({
			'url': url,
			'type': "POST",
			'data': { sessionId:sessionId, groupId:groupId, start:start, limit:limit},
			'beforeSend': function() {
				$('.d-upload_status').text('Идет загрузка данных...');
			},
			'success':function(data) {
				$('.d-upload_status').html(data);
			},
			'error':function(data) {
				$('.d-upload_status').html(data);
			}
		})
		return false;
	})
</script>