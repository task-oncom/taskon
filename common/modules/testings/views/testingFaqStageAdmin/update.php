<?php

$this->tabs = array(
    'управление' => $this->createUrl('manage', array('faq' => $model->faq_id)),
);

echo $form;