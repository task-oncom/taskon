<?php
/* @var $this yii\web\View */
?>
<div class="container-fluid">
    <div class="main-content">

<div class="faq">

    <div class="faq-questions-users">

        <div class="faq-questions-search">
            <form action="<?php echo \yii\helpers\Url::toRoute('all')?>" method="get">
                <label>Поиск по вопросам</label>
                <div class="faq-questions-search-field">
                    <!--input type="text" placeholder="Например: как получить деньги на карту" class="form-control" name="search"-->
                    <?php
                    $search = '';
                    if(!empty($_GET['search'])) $search = $_GET['search'];

                    echo \yii\helpers\Html::textInput("search",$search, [
                        'placeholder'=>"Например: как получить деньги на карту",
                        'class'=>"form-control",

                    ])?>
                    <button class="btn-search">Найти</button>
                </div><!-- /faq-questions-search-field -->
            </form>
        </div><!-- /faq-questions-search -->

        <hr>

        <div class="row">
            <div class="col-sm-7 col-md-8">

                <h3 class="faq-questions-heading">Вопросы от пользователей</h3><!-- /faq-questions-heading -->

                <?php echo \yii\widgets\ListView::widget( [
                    'dataProvider' => $dataProvider,
                    'itemView' => '_index-view',
                    'itemOptions' => ['tag' => 'li'],
                    'options' => ['tag' => 'ul', 'class' => 'faq-questions-users-list'],
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

            </div><!-- /col-sm-7 col-md-8 -->
            <?php echo $this->render('faq-form', ['model' => $model])?>
        </div><!-- /row -->

    </div><!-- /faq-questions-users -->

</div><!-- /faq -->

    </div>
</div>

<?php

?>
