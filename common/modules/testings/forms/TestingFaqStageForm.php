<?php


return array(
    'activeForm' => array(
        'id' => 'testing-faq-form',
    ),
    'elements' => array(
        'title' => array('type' => 'text'),
		'content' => array('type' => 'editor'),
	),
    'buttons' => array(
        'submit' => array(
            'type'  => 'submit',
            'value' => $this->model->isNewRecord ? 'создать' : 'сохранить',
		),
    ),
);


