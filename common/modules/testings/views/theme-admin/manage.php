<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\Test;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\faq\models\SearchFaq */
/* @var $dataProvider yii\data\ActiveDataProvider */

$test_id = null;
$test = \Yii::$app->request->get('test');

if ($test) 
{
	$test = Test::findOne($test);
	if ($test) 
	{
		$test_id = $test->id;
	}
}
?>

<p>
    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>

    <?php if($question_id) : ?>
        <?= Html::a('Импорт вопросов из XLS-файла', ['testings/test-admin/import-tests', 'id' => $test_id], ['class' => 'btn btn-info']) ?>
    <?php endif; ?>
</p>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

   		'name',
		[
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {update}',
        ],
    ],
]); ?>