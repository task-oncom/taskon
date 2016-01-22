<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\TestingTest;
use common\modules\testings\models\TestingPassing;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<style type="text/css">
	.STATE1 {
		font-weight: bold;
		color: #449944;
	}
	.STATE0, .STATE2 {
		font-weight: bold;
		color: #ee4444;
	}
	.STATE, .STATE3 {
		color: #999;
	}
</style>



<p>
    <?= Html::a('Добавить', ['create', 'session' => $session->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Импорт из CSV-файла', ['/testings/testing-session-admin/import-passings', 'id' => $session->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Экспорт результатов в CSV-файл', ['/testings/testing-session-admin/export-session-result', 'id' => $session->id], ['class' => 'btn btn-success']) ?>
</p>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

    	[
            'attribute' => 'user_id',
            'format' => 'html',
            'value' => function($model)
            {
            	if($model->user)
            	{
            		return Html::a($model->user->fio, ["/testings/testing-user-admin/view", "id" => $model->user->id]);
            	}
            	else
            	{
            		return "Пользователь удален";
            	}
            }
        ],
        [
            'attribute' => 'filter_user_email',
            'format' => 'html',
            'value' => function($model)
            {
            	return Html::a($model->user->email, "mailto:" . $model->user->email);
            }
        ],
        [
            'attribute' => 'filter_user_company_name',
            'format' => 'html',
            'value' => function($model)
            {
            	return $model->user->company_name;
            }
        ],
        [
            'attribute' => 'test_id',
            'format' => 'html',
            'filter' => TestingTest::getTestsList($session->id),
            'value' => function($model)
            {
            	return Html::a($model->test->name, ["/testings/testing-test-admin/view","id" => $model->test_id]);
            }
        ],
        [
            'attribute' => 'is_passed',
            'format' => 'html',
            'filter' => TestingPassing::$state_list,
            'value' => function($model)
            {
            	return Html::tag("span", TestingPassing::$state_list[$model->status], ["class" => "STATE" . $model->status]);
            }
        ],
		'pass_date',
		[
            'header' => 'Ответственный менеджер',
            'format' => 'html',
            'value' => function($model)
            {
            	if($model->user && $model->user->manager)
            	{
            		return Html::a($model->user->manager->name, ["/users/user-admin/view", "id" => $model->user->manager->id]);
            	}
            	else
            	{
            		return "Пользователь удалён";
            	}
            }
        ],
		[
            'header' => 'Загрузить сообщение об ошибке',
            'format' => 'html',
            'filter' => false,
            'value' => function($model)
            {
            	if($model->is_passed !== null)
            	{
            		if($model->mistake)
            		{
            			$text = "Сведения " . $model->mistake->create_date;
            		}
            		else
            		{
            			$text = "Загрузить";
            		}
            		return Html::a($text, ["/testings/testing-passing-admin/mistake", "id" => $model->id]);
            	}
            	else
            	{
            		return "";
            	}
            }
        ],
        [
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {delete}',
            'contentOptions' => ['style' => 'width:60px;', 'align' => 'center'],
        ],
    ],
]); ?>
