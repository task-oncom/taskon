<?php

$this->tabs = array(
	'список назначений тестов пользователям' => $this->createUrl('/testings/testingUserAdmin/manage', array('session' => $session_id)),
);

$this->page_title = 'История отправок дубликатов';

$this->widget('AdminGrid', array(
	'id' => 'testing-sendhistory-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		'email',
		array(
			'name' => 'sended',
			'value' => 'date("d.m.Y H:i:s", $data->sended)',
		),
		array(
			'name' => 'unisender_status',
			'value' => 'TestingSendHistory::getStatusTitle($data->unisender_status)',
			'filter' => TestingSendHistory::getStatusTitle(),
			'headerHtmlOptions' => array(
				'style' => 'width: 360px;'
			)
		),
		array(
			'header' => '',
			'type' => 'raw',
			'value' => function($data) {
				if($data->file && file_exists($data->getFilePath()))
				{
					$send = CHtml::link(CHtml::image('/images/icons/mail.png', 'Повторить отправку', array('title' => 'Повторить отправку')), '#', array(
						'data-id' => $data->id, 
						'data-email' => $data->email, 
						'id' => "resend"
					));
					$file = CHtml::link(CHtml::image('/img/excel_bg.png', 'Скачать список доступов', array('title' => 'Скачать список доступов')), $data->getFileUrl());

					return $send . ' ' . $file;
				}
			}
		)

	),
	'template' => "{pagerSelect}{summary}<br/>{pager}<br/>{items}<br/>{pager}",
));
