<?php

$this->page_title = 'Просмотр группы';

$this->tabs = array(
    'группы'        => $this->createUrl('manage'),
    'редактировать' => $this->createUrl('update', array('id' => $model->name)),
    'добавить'      => $this->createUrl('create')
);

$this->widget('DetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        'description',
        'bizrule',
        'data'
    )
));
