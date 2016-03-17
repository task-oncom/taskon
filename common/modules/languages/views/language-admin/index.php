<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\modules\languages\models\Languages;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\languages\models\SearchLanguages */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'url',
            'local',
            'name',
            [
                'attribute' => 'default',
                'filter' => Html::dropDownList($searchModel->formName() . '[default]', $searchModel->default, Languages::$defaults_title, ['class' => 'form-control', 'prompt' => '']),
                'value' => function($model) 
                {
                    return Languages::$defaults_title[$model->default];
                }
            ],

            [
				'class' => 'common\components\ColorActionColumn',
				'template' => '{update} {delete}',
			],
        ],
    ]); ?>


