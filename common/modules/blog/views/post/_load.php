<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Post */

?>

<?php foreach ($models as $model) : ?>

    <article class="article_short">
        <a href="<?=Url::to(['/blog/'.$model->url])?>" class="article_short_title"><?=$model->lang->title?></a>
        <div class="article_short_head">
            <span class="article_short_date"><?=date('d.m.Y', $model->created_at)?></span>
            <span class="article_short_view">
                <?=$model->getViews()->count()?>
                <div class="blog_toltip_left">Количество просмотров</div>
            </span>            
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

            <?=$model->lang->cutText(650)?>
        </div>
    </article>

<?php endforeach; ?>