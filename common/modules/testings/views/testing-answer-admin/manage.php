<?php

use yii\helpers\Html;
use \common\components\zii\AdminGrid;

use common\modules\testings\models\TestingAnswer;
use common\modules\testings\models\TestingQuestion;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\faq\models\SearchFaq */
/* @var $dataProvider yii\data\ActiveDataProvider */

$question_id = null;
$question = \Yii::$app->request->get('question');

if ($question) 
{
	$question = TestingQuestion::find()->where(['id' => $question])->one();
	if ($question) 
	{
		$question_id = $question->id;
	}
}
?>

<p>
    <?php if($question_id) : ?>
        <?= Html::a('Добавить', ['create', 'question' => $question_id], ['class' => 'btn btn-success']) ?>
    <?php else : ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
</p>

<?php echo AdminGrid::widget([
    'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
    'columns' => [
       	// ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'question_id',
            'filter' => false,
            'value' => function($model)
            {
            	return $model->question->text;
            }
        ],
        'text',
        [
            'attribute' => 'is_right',
            'filter' => TestingAnswer::$type_list,
            'value' => function($model)
            {
            	return TestingAnswer::$type_list[$model->is_right];
            }
        ],
        [
            'class' => 'common\components\ColorActionColumn',
	        'template' => '{view} {update}',
            'contentOptions' => ['style' => 'width:60px;', 'align' => 'center'],
        ],
    ],
]); ?>

