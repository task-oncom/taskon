<?php

$this->tabs = array(
    'управление' => $this->createUrl('manage', array('faq' => Yii::app()->request->getQuery('faq')))
);

echo $form;

