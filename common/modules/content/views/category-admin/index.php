<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\content\models\SearchCoCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('content', 'Create Category'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="alert alert-success m-b-0" style="background:#79C137;">
        <p>
            Для того что бы было проще идентифицировать статические страницы и инфо-блоки страниц мы добавили возможность каждую создаваемую страницу привязывать к Категории. Это позволяет в дальнейшем сортировать страницы по категориям.
        </p>
    </div>

    <br>

    <?= \common\components\zii\AdminGrid::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Название категории'
            ],
            /*'url:url',*/

            [
                'class' => 'common\components\ColorActionColumn',
                'headerOptions' => [
                    'style' => 'width: 150px;'
                ]
            ],
        ],
    ]); ?>


