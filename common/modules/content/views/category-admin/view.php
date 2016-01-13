<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\content\models\CoCategory */

?>


    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('content', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('content', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'url:url',
        ],
    ]) ?>

    <p>
        <?= Html::a(Yii::t('content', 'Create Content'), ['/content/content-admin/create', 'category_id'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute' => 'category_id',
				'format' => 'text',
				'value' => function($data) {
					return $data->category->name;
				}
			],
            'url:url',
            'name',
            'title',
            // 'active',
            // 'created_at',
            // 'updated_at',

            ['class' => 'common\components\ColorActionColumn'],
        ],
    ]); ?>
