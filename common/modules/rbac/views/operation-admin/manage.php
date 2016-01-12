<?php 
\yii::$app->controller->page_title = 'Операции'; 

\yii::$app->controller->tabs = array(
    "Добавить операцию" => \yii\helpers\Url::toRoute("create"),
    "Добавить все операции модулей" => \yii\helpers\Url::toRoute("addAllOperations")
);

echo \common\components\zii\AdminGrid::widget([
	'id' => 'operations-grid',
	'dataProvider' => $model/*->search(\common\modules\rbac\models\AuthItem::TYPE_OPERATION)*/,
	'filterModel'       => $search,
	'class' => 'table table-striped table-bordered nowrap',
	'columns' => [
        [
            'attribute'  => 'name',
            /*'value' => '$data->actionExists() ? "$data->name" : "$data->name &nbsp;&nbsp;&nbsp; <span class=\"red_font\">действие не существует</span>"',*/
			'value' => function($data){
							return $data->actionExists() ? $data->name : $data->name .'&nbsp;&nbsp;&nbsp; <span class="red_font">действие не существует</span>';},
            'format'  => 'raw'
        ],
        'description',
        /*[
			//'name' => 'allow_for_all', 
			'value' => '$data->allow_for_all ? "Да" : "Нет"'
		],*/
		[
			'class' => 'common\components\ColorActionColumn',
		],
	],
]); 

