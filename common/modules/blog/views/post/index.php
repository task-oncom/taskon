<?php

use yii\helpers\Html;
use yii\helpers\Url;

use common\modules\blog\models\Post;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Post */

?>

<div class="blog_container">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-xs-8 col-sm-12">
                <section class="blog">

                    <h1><?=Yii::t('menu', 'Blog')?></h1>

                    <?=$this->render('_load', ['models' => $models])?>
    
                    <?php if($count > count($models)) : ?>
                        
                        <div class="loaded">
                            
                        </div>
                        <div class="load-box">
                            <a href="#" class="sidebar_btn" id="load-post" data-offset="<?=Post::PAGE_SIZE?>" style="display:block; margin: 0 auto;">Загрузить еще</a>
                            <div class="loading-post"></div>
                        </div>

                    <?php endif; ?>

                    <?=$this->render('_subscribe', ['title' => 'Страница Блог'])?>

                </section>
            </div>
            
            <?=$this->render('_sidebar')?>

        </div>
    </div>
</div>

<?=$this->render('_modals')?>

<?=$this->render('@app/views/layouts/footer');?>