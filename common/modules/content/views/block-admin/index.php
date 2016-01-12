<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\helpers\ArrayHelper;
use \common\modules\content\models\CoCategory;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\content\models\SearchCoBlocks */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('content', 'Create Co Blocks'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute'=>'category_id',
                'value' => function($data) {
                    if (!empty($data->category_id)) {
                        $model = CoCategory::find()->where(['id' => $data->category_id])->one();
                        if(!empty($model))
                            return $model->name;
                    }
                    return 'не задано';
                },
                'filter' => Html::dropDownList('SearchCoBlocks[category_id]',
                    $searchModel->category_id,
                    ArrayHelper::map(CoCategory::find()->all(),'id','name'),
                    ['class'=>'form-control','prompt'=>'Категория'])
            ],
            'title',
            'name',
            //'text:ntext',
            // 'date_create',

            [
                'class' => 'common\components\ColorActionColumn',
                'template' => '{update} {delete}',
            ]
                ,
        ],
    ]); ?>


