<?php

$this->tabs = array(
    //'управление' => $this->createUrl('manage'),
    'просмотр'   => $this->createUrl('view', array('passing' => $form->model->passing_id))
);

echo $form;