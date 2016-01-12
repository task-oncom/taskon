<?php
class AadminGrid extends GgridView
{
    public $pager = array('class'=> 'AdminLinkPager');
    public $cssFile = "/css/admin/gridview/styles.css";
    public $template = '{pagerSelect}{summary}<br/><br/>{pager}<br/>{pocket}{items}<br/>{pager}';

    public function registerClientScript()
    {
        parent::registerClientScript();

        Yii::app()->clientScript->registerScript($this->getId().'CmsUI', "
            $('#{$this->getId()}').grid();
        ");
    }
}