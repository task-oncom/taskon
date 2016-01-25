<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;
use yii\helpers\Url;

use common\modules\testings\models\TestingSendHistory;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	// 'filterModel' => $searchModel,
    'columns' => [
       // ['class' => 'yii\grid\SerialColumn'],

    	'email',
		[
			'attribute' => 'sended',
			'value' => function($model)
			{
				return date("d.m.Y H:i:s", $model->sended);
			}
		],
		[
			'attribute' => 'unisender_status',
			'value' => function($model)
			{
				return TestingSendHistory::getStatusTitle($model->unisender_status);
			},
			'filter' => TestingSendHistory::getStatusTitle(),
		],
		[
            'class' => \common\components\ColorActionColumn::className(),
	        'template' => '{send} {file}',
	        'buttons' => [
	        	'send' => function ($url, $model, $key)
	        	{
	        		if($model->file && file_exists($model->getFilePath()))
	        		{
				        return Html::a('<i class="fa fa-envelope fa-lg"></i>', Url::to(['testings/testing-session-admin/send-message', 'id' => $session->id, 'user' => $model->id]), [
		                    'title' => 'Уведомить о тестировании',
		                    'data-toggle' => 'tooltip',
		                    'data-pjax' => '0',
		                ]);
		            }
			    },
			    'file' => function ($url, $model, $key)
	        	{
	        		if($model->file && file_exists($model->getFilePath()))
	        		{
	        			return Html::a('<i class="fa fa-file-text-o fa-lg"></i>', $model->getFileUrl(), [
		                    'title' => 'Скачать список доступов',
		                    'data-toggle' => 'tooltip',
		                    'data-pjax' => '0',
		                ]);
	        		}			        
			    },
	        ]
        ]
    ],
]); ?>