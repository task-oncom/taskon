<?php
//use common\components\GridView;
namespace common\components\zii;
use common\components\zii\AdminLinkPager;
class AdminGrid extends GridView
{   

    public $sortable = false;
    public $pager = array('class'=> '\common\components\zii\AdminLinkPager');
    public $cssFile = "/css/admin/gridview/styles.css";
    //public $layout = '<div class="pull-right">{pagerSelect}</div>{clipboard}<br/>{items}{summary}{pager}';
	//public $layout = '{pagerSelect}{search}{items}{summary}{pager}';
    public $layout = '{items}{pager}';
	
	public $summaryOptions = ['class' => 'dataTables_info'];
 	
	
    public function registerClientScript()
    {
        parent::registerClientScript();

        Yii::app()->clientScript->registerScript($this->getId().'CmsUI', "
            $('#{$this->getId()}').grid();
        ");
    }
	
	public function run() {
		parent::run();
		$view = $this->getView();

        $view->registerCssFile('/plugins/DataTables/css/data-table.css', ['position'=>$view::POS_HEAD]);

        $view->registerJsFile('/plugins/jquery/jquery-1.9.1.min.js', ['position'=>$view::POS_END]);
		$view->registerJsFile('/plugins/jquery-ui/ui/minified/jquery-ui.min.js', ['position'=>$view::POS_END]);
		$view->registerJsFile('/plugins/jquery-ui/ui/minified/jquery.ui.widget.min.js', ['position'=>$view::POS_END]);
		
		$publish = \Yii::$app->assetManager->publish(
			\Yii::getAlias('@app/../common/js/plugins')
		);
		$view->registerJsFile($publish[1].'/gridview/gridBase.js', ['position'=>$view::POS_END]);
		$view->registerJsFile($publish[1].'/gridview/grid.js', ['position'=>$view::POS_END]);
		$view->registerJsFile($publish[1].'/gridview/pocket.js', ['position'=>$view::POS_END]);

        $view->registerJsFile('/plugins/DataTables/js/jquery.dataTables.js', ['position'=>$view::POS_END]);
        $view->registerJsFile('/plugins/DataTables/js/dataTables.colVis.js', ['position'=>$view::POS_END]);
        $view->registerJsFile('/js/table-manage-colvis.demo.js', ['position'=>$view::POS_END]);
        $view->registerJs('TableManageColVis.init();', $view::POS_READY);
        //$view->registerCss('#DataTables_Table_0_length {display: none;}', $view::POS_HEAD);
	}

    protected function registerWidget()
    {
        if($this->sortable) {
            $view = $this->getView();
            $view->registerJs("jQuery('#{$this->id}').SortableGridView('{$this->sortableAction}');");
            SortableGridAsset::register($view);
        }
    }
}