<script>
    $(document).ready(function() {
		$('.session').css('display', 'none');
		$('#sessions_list').bind('change', function () {
			$('.session').css('display', 'none');
			id = $(this).val();
			$('#'+id).css('display', 'block');
		});
		$('#sessions_list:first').change();
	});
</script>
<style>
	.session {
		width: 1000px;
		margin-top: 10px;
	}
	.session td {
		width: 135px;
		text-align: center;
	}
	.session tr {
		height: 100px;
	}
</style>
<?php

$this->crumbs = array(
	'Тестирование' => false,
);

$this->page_title = 'Список прохождений';
?>

<span style="font-size: 12px; float: right;"><?php echo ' (' .CHtml::link('Выход',array('/testings/testingTest/logout')) . ')'; ?> </span>

<?php
	if ($sessions == null) {
		echo 'нет сессий';
	}
	else {
		echo 'Выберите сессию:';
		echo CHtml::dropDownList('sessions_list', null, CHtml::listData($sessions, 'id', 'name'));
	}
	
	if ($passings) {
		foreach ($passings as $session_id => $session) {
			echo '<div id = "'.$session_id.'" class="session">
				<table border="1">
				<tr><td>ФИО</td><td>Email</td><td>Город</td><td>Компания</td><td>Тест</td><td>Результат</td></tr>';
			foreach ($session as $user) {
				?>
				<tr>
					<td style="width:220px;"><?php echo $user->user->first_name.' '.$user->user->last_name.' '.$user->user->patronymic; ?></td>
					<td><?php echo $user->user->email; ?></td>
                    <td><?php echo $user->user->city; ?></td>
					<td><?php echo $user->user->company_name; ?></td>
					<td style="width:220px;"><?php echo $user->test->name; ?></td>
					<td><?php echo TestingPassing::$state_list[$user->status].' '.$user->pass_date; ?></td>
				</tr>
				<?php
			}
			echo '</table></div>';
		}
	}
	else {
		echo 'Пользователи не загруженны в сессии';
	}
?>
