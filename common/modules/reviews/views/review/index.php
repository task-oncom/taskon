<?php
/* @var $this yii\web\View */
?>

<div class="container-fluid">
    <div class="main-content">

        <div class="reviews">

            <div class="reviews-ctrl">
                <a href="/scoring/register/register" class="btn btn-green">Оставить отзыв</a>
                <h1 class="reviews-heading">Реальные отзывы о компании Соцзайм</h1><!-- /reviews-heading -->
            </div><!-- /reviews-ctrl -->

            <!--ul class="reviews-list">
                <?php //foreach($model as $review):?>
                    <?php //echo $this->render('item-view',['review'=>$review])?>
                <?php //endforeach?>
            </ul-->

            <?php echo \yii\widgets\ListView::widget( [
                'dataProvider' => $dataProvider,
                'itemView' => 'item-view',
                'itemOptions' => ['tag' => 'li'],
                'options' => ['tag' => 'ul', 'class' => 'reviews-list'],
                'layout' => '{items}<li><nav class="pages">{pager}</nav></li>',
                'pager' => [
                    'class' => 'common\components\zii\FrontLinkPager',
                    'activePageCssClass' => 'is-active',
                    'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>Предыдущая',
                    'nextPageLabel' => 'Следующая<span class="glyphicon glyphicon-chevron-right"></span>',
                    'options' => [
                        'class' => 'pages-list',
                    ],
                ],
            ] );
            ?>
            <!-- /reviews-list -->

        </div><!-- /reviews -->

    </div><!-- /main-content -->
</div><!-- /container-fluid -->