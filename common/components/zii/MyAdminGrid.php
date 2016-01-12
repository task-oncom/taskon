<?php
//use common\components\GridView;
namespace common\components\zii;
use common\components\zii\AdminLinkPager;
class AdminGrid extends \yii\grid\GridView
{   

    
	public $tableOptions = ['class' => 'table table-striped table-bordered dataTable no-footer'];
	public $options = ['class' => 'table-responsive'];
	public $layout = "{summary}\n{items}\n{pager}";
	
	
	public $pager = array('class'=> '\common\components\zii\AdminLinkPager'); 
    public $cssFile = "/css/admin/gridview/styles.css";
    public $layout = '<div class="pull-right">{pagerSelect}{summary}{pager}</div>{clipboard}<br/>{items}<br/><div class="pull-right">{pager}</div>';
	
	public function run() {
		parent::run();
	}
}