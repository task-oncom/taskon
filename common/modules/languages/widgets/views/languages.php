<?php
use yii\helpers\Html;
?>

<?php if($count > 2) : ?>
    <div class="lang_check">
        <?= Html::a('<i class="icon-arrowDown"></i>' . $current->name, '#', ['class' => 'd_menu']) ?>
        <?php foreach ($langs as $i => $lang): ?>
            <?= Html::a('<i class="icon-arrowRight"></i>' . $lang->name, (!$lang->default?'/'.$lang->url:'') . (Yii::$app->getRequest()->getLangUrl()==''?'/':Yii::$app->getRequest()->getLangUrl()), ['class' => 'd_menu_hide']) ?>
        <?php endforeach;?>
    </div>
<?php else : ?>
    <nav class="top_nav clearfix">
        <ul>
            <?php foreach ($langs as $lang):?>
                <li><?= Html::a($lang->name, (!$lang->default?'/'.$lang->url:'') . (Yii::$app->getRequest()->getLangUrl()==''?'/':Yii::$app->getRequest()->getLangUrl())) ?></li>
            <?php endforeach;?>
        </ul>
    </nav>
<?php endif; ?>