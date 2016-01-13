<?php
$this->page_title = "Просмотр Пользователя: {$model->name}";

$this->tabs = array(
    "управление пользователями" => $this->createUrl("manage"),
    "редактировать" => $this->createUrl("update", array("id" => $model->id))
);

if(!empty($_GET['is_created'])) {
    echo '<span style="font-weight: bold; font-size: 10px; display: inline-block; margin-bottom: 4px;">
            Пользователь был успешно создан. Для подтверждения e-mail адреса, вы должны перейдя по ссылке указанной в письме, которое было отправлено Вам на почтовый адрес.
            <br />Если письмо вам не пришло, то проверьте его в СПАМе или вышлите повторно.
          </span>';
}

$this->widget('application.components.DetailView', array(
	'data' => $model,
	'attributes' => array(
		'fio',
		'email',
		'phone',
		array(
			'name'  => 'role',
			'value' => $model->role->description
		),
		array(
			'name'  => 'status',
			'value' => User::$status_list[$model->status]
		),
		'date_create'
	),
));
?>
