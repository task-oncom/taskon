<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\languages\models\SearchLanguages */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('languages', 'Create Languages'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'codeFull',
            'name',

            [
				'class' => 'common\components\ColorActionColumn',
				'template' => '{update} {delete}',
			],
        ],
    ]); ?>


