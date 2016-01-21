<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\modules\testings\models\TestingAnswer;

/* @var $this yii\web\View */

?>

<div class="faq-view">

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <!-- <?= Html::a(Yii::t('content', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('content', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			'question_id',
			'text',
			[
				'attribute' => 'is_right',
            	'value' => TestingAnswer::$type_list[$model->is_right],
			],
			'create_date',
        ],
    ]) ?>

</div>


