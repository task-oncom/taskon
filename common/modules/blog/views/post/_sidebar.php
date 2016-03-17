<?php

use yii\helpers\Url;

use common\modules\blog\models\Post;
use common\modules\blog\models\PostTag;
use common\models\Settings; 

?>

<div class="col-md-4 col-xs-4 col-sm-12">
    <aside class="sidebar">
        <div class="sidebar_module">
            <h2>Категории</h2>
            <div class="sidebar_module_body">

                <?php $tags = PostTag::find()
                    ->innerJoinWith('posts')
                    ->groupBy(['posts_tags_assign.tag_id'])
                    ->where(['posts.active' => 1])
                    ->orderBy('RAND()')
                    ->limit(7)
                    ->all();
                foreach ($tags as $tag) : ?>
                
                    <a href="<?=$tag->url;?>" class="cat_link_mod"><?=$tag->name?></a> 

                <?php endforeach; ?>

            </div>
        </div>
        <div class="sidebar_module">
            <div class="sidebar_module_body"> 
                <a href="#feedback" class="sidebar_btn popup-form">
                    Предложить тему
                    <div class="blog_toltip_right">Предложите свою тему и мы напишем</div>
                </a> 
                <a href="#article" class="sidebar_btn popup-form">Послать статью</a>
            </div>
        </div>

        <?php $posts = Post::find();
        if($model)
        {
            $posts = $posts->where(['!=', 'id', $model->id]);
        } 
        $posts = $posts->andWhere(['active' => 1])->orderBy('RAND()')->limit(3)->all(); 
        if($posts) : ?>
            <div class="sidebar_module">
                <h2>Популярные</h2>
                <div class="sidebar_module_body">

                    <?php foreach ($posts as $post) : ?>

                        <div class="article_itm">
                            <?php if($post->preview) : ?>
                                <img src="<?=$post->getThumbnailUrl()?>" alt="">
                            <?php endif; ?>
                            <a href="<?=Url::to(['/blog/'.$post->url])?>" class="article_itm_link"><?=$post->lang->title?></a>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        <?php endif; ?>

        <div class="sidebar_module">
            <h2>Все секреты в наших соц сетях!</h2>
            <div class="sidebar_module_body">
                <ul class="sidebar_social">
                    <?php $s = Settings::getValue('social-link-fb'); if($s): ?><li><a href="<?=$s?>" class="sidebar_social_fb"></a></li><? endif; ?>
                    <!-- <li><a href="#" class="sidebar_social_ok"></a></li> -->
                    <?php $s = Settings::getValue('social-link-twitter'); if($s): ?><li><a href="<?=$s?>" class="sidebar_social_tw"></a></li><? endif; ?>
                    <?php $s = Settings::getValue('social-link-google'); if($s): ?><li><a href="<?=$s?>" class="sidebar_social_gp"></a></li><? endif; ?>
                    <?php $s = Settings::getValue('social-link-youtube'); if($s): ?><li><a href="<?=$s?>" class="sidebar_social_yt"></a></li><? endif; ?>
                    <?php $s = Settings::getValue('social-link-vk'); if($s): ?><li><a href="<?=$s?>" class="sidebar_social_vk"></a></li><? endif; ?>
                    <?php $s = Settings::getValue('social-link-instagram'); if($s): ?><li><a href="<?=$s?>" class="sidebar_social_inst"></a></li><? endif; ?>
                </ul>
            </div>
        </div>
    </aside>
</div>