<?php
namespace common\components\zii;
class AdminGrid extends \common\components\CGridView
{   

    public $pager = array('class'=> '\common\components\zii\AdminLinkPager'); 
    public $cssFile = "/css/admin/gridview/styles.css";
    public $template = '{pagerSelect}{summary}<br/><br/>{pager}<br/>{clipboard}<br/>{items}<br/>{pager}';
 
    public function registerClientScript()
    {
        parent::registerClientScript();

        Yii::app()->clientScript->registerScript($this->getId().'CmsUI', "
            $('#{$this->getId()}').grid();
        ");
    }
}