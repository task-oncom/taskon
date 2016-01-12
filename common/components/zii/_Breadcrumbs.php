<?php
Yii::import('zii.widgets.CBreadcrumbs');
class Breadcrumbs extends CBreadcrumbs
{
    public $homeLink = false;
    public $htmlOptions=array('class' => 'breadcrumbs');
    public $separator='<span> / </span>';
    public $currentPageClass = 'current';


}

