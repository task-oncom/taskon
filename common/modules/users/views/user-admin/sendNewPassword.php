<?php
$this->page_title = 'Редактирование пользователя' . $form->model->name;

$this->tabs = array(
    "управление пользователями" => $this->createUrl("manage"),
);
?>

<?php
	echo $form;
?>

<script type="text/javascript">
		$('.checkbox').mouseup(function(){
			var ch = jQuery('#User_generate_new');
			if (ch.attr('checked')) {
				$('#User_password').removeAttr('disabled');
				$('#User_password_c').removeAttr('disabled');
			} else {
				$('#User_password').attr('disabled','disabled');
				$('#User_password_c').attr('disabled','disabled');
				$('#User_password').val('');
				$('#User_password_c').val('');
			}
		});
</script>
