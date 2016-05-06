

<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\modules\rbac\models\AuthItem;
use common\components\zii\AdminGrid;


\Yii::$app->controller->tabs = [
    "Создать пользователя" => Url::toRoute("create")
];
?>
<p>
        <?= Html::a(Yii::t('units', 'Создать нового пользователя', [
    'modelClass' => 'Modules',
]), ['/users/user-admin/create'], ['class' => 'btn btn-success']) ?>
</p>
<?php
function tasksLink($role)
{
    $url = Url::toRoute(['/rbac/RoleAdmin/ModuleOperations', 'role'=>$role]);
	return "<a href='{$url}'>перейти</a>";
}

$not_system_role = '!in_array($data->name, AuthItem::$system_roles)';


echo AdminGrid::widget([
	'id' => 'access-grid-roles',
	'dataProvider' => $dataProvider,
	'formatDateValues' => false,
    'rowOptions' => function ($model, $index, $widget, $grid){
        if($model->status == 'blocked')
            //return ['style'=>'background-color:#575d63 !important;'];
            return ['class'=>'bg-silver-lighter'];
        else return [];
    },
//	'filterModel'   => $searchModel,
    'sortable' => false,
	'columns' => $modules['columns'],
]);


		$view = $this;
		$view->registerCss('.table.table-striped.table-bordered.dataTable.DTFC_Cloned .sc.sortt.ui-sortable tr {height: 59px;}');


$url = \yii\helpers\Url::toRoute('changeaccess');		
$js = <<<JS
	$(document).on("click","span.switchery", function(){
		var state = $(this).prev().attr("checked");
		var user_id = $(this).prev().attr("user-id");
		var item = $(this).prev().attr("item");
		var op = '';
		if(state == 'checked') op = 'assign';
		else op = 'remove';
		
		$.ajax({
			url: '$url',
			data: 'op='+op+'&item='+item+'&user_id='+user_id
		});
	})
JS;
		$view->registerJs($js, $view::POS_READY);
?>
