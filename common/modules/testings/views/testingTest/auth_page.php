<?php
	$this->crumbs = array(
		'Тестирование' => array('//testings/testingTest/index'),
		'Вход в раздел тестирование' => false,
	);
?>

<div class="center">
	<form method="post">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="first_col">Логин</td>
				<td class="second_col">
					<p><input name="username" type="text" value="" /></p>
				</td>
			</tr>
			<tr>
				<td class="first_col">Пароль</td>
				<td class="second_col">
					<input name="password" type="password" value="" /> &nbsp;
				</td>
			</tr>
			<tr>
				<td class="first_col">&nbsp;</td>
				<td class="second_col">
					<input type="submit" value="Войти" class="submit" />
					<div class="clear"></div>
				</td>
			</tr>
		</table>
	</form>
</div>