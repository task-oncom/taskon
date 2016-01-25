<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\TestingTest;
use common\modules\testings\models\TestingQuestion;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$test_id = null;
$test = \Yii::$app->request->get('test');

if ($test) 
{
	$test = TestingTest::find()->where(['id' => $test])->one();
	if ($test) 
	{
		$test_id = $test->id;
	}
}
?>

<?php
$js = <<<EOD
$("#message_expand").one("click", function (e) {
    e.preventDefault();
    var el = $(this).hide();
        
    $('#list_expand').show();
    
});
EOD;
$this->registerJs($js, yii\web\View::POS_READY, 'expnd.info');
?>

<?php if(count($questions)):?>
	<div class="message info">
	    <a href="#" id="message_expand">Показать</a>
	    <p>Имеются вопросы со словами: <b>&laquo<?php echo Html::encode($questions_param);?>&raquo</b></p>
	    <br/><br/>
	    <ul style="display:none;" id="list_expand">
	        <?php foreach($questions as $id => $text): ?>
	        	<li><?php echo Html::a(Html::encode($text), ['update', 'id' => $id]); ?></li>
	        <?php endforeach; ?>
	    </ul>
	</div>
<?php endif;?>

<p>
    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

    	[
            'header' => 'Сессия',
            'format' => 'html',
            'visible' => !$test_id,
            'filter' => false,
            'value' => function($model)
            {
            	if($model->test && $model->test->session)
            	{
            		return Html::a($model->test->session->name, ["/testings/testing-session-admin/view", "id" => $model->test->session_id]);
            	}
            	else
            	{
            		return "Сессия удалена";
            	}
            }
        ],
        [
            'header' => 'Тест',
            'format' => 'html',
            'visible' => !$test_id,
            'filter' => false,
            'value' => function($model)
            {
            	if($model->test)
            	{
            		return Html::a($model->test->name, ["/testings/testing-test-admin/view", "id" => $model->test_id]);
            	}
            	else
            	{
            		return "Тест удален";
            	}
            }
        ],
        [
            'header' => 'Тема',
            'format' => 'html',
            'visible' => !$test_id,
            'filter' => false,
            'value' => function($model)
            {
            	if($model->theme)
            	{
            		return Html::a($model->theme->name, ["/testings/testing-theme-admin/view", "id" => $model->theme_id]);
            	}
            	else
            	{
            		return "Тема удалена";
            	}
            }
        ],
        'text',
        [
            'attribute' => 'theme_id',
            'format' => 'html',
            'visible' => $test_id,
            'filter' => TestingQuestion::getThemesList(),
            'value' => function($model)
            {
            	if($model->theme)
            	{
            		return Html::a($model->theme->name, ["/testings/testing-theme-admin/view", "id" => $model->theme_id]);
            	}
            	else
            	{
            		return "Тема удалена";
            	}
            }
        ],
        [
            'header' => 'Ответы',
            'format' => 'html',
            'filter' => false,
            'value' => function($model)
            {
            	return Html::a('Подробнее', ["/testings/testing-answer-admin/manage", "question" => $model->id]);
            }
        ],
        [
            'attribute' => 'is_active',
            'filter' => TestingQuestion::$active_list,
            'value' => function($model)
            {
            	return TestingQuestion::$active_list[$model->is_active];
            }
        ],
        [
            'attribute' => 'type',
            'filter' => TestingQuestion::$type_list,
            'value' => function($model)
            {
            	return TestingQuestion::$type_list[$model->type];
            }
        ],
        [
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {update}',
        ],
    ],
]); ?>