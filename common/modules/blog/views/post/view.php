<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Post */

?>


<div class="blog_container">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-xs-8 col-sm-12">
                <section class="blog">
                    <h1><?=$model->lang->title?></h1>
                    <article class="article_short">
                        <div class="article_short_head">
                            <span class="article_short_date"><?=date('d.m.Y', $model->created_at)?></span>
                            <span class="article_short_view">
                                <?=$model->getViews()->count()?>
                                <div class="blog_toltip_left">Количество просмотров</div>
                            </span>

                            <?=$this->render('_social', ['link' => Url::to(['/blog/'.$model->url], true), 'title' => $model->lang->title])?>

                        </div>
                        <div class="article_short_tags">

                            <?php foreach ($model->postTags as $tag) : ?>

                                <a href="<?=$tag->url;?>">#<?=$tag->name?></a>

                            <?php endforeach; ?>
                            
                        </div>
                        <div class="article_short_txt">

                            <div class="preview-image">
                                <?php if($model->preview) :
                                    echo Html::img($model->preview);
                                endif; ?>
                            </div>

                            <?=$model->lang->text?>
                        </div>
                    </article>
                    
                    <?=$this->render('_subscribe', ['title' => 'Запись в блоге: ' . $model->lang->title])?>

                </section>
            </div>
            
            <?=$this->render('_sidebar', ['model' => $model])?>

        </div>
    </div>
</div>

<?=$this->render('_modals')?>

<?=$this->render('@app/views/layouts/footer');?>
