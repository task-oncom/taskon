<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?php echo Html::encode(\Yii::$app->controller->meta_title)?></title>
    <?php $this->head() ?>
    <?php echo $this->render('head')?>
</head>
<body>

    <?php $this->beginBody() ?>

    <a class="btn-scroll btn-scroll-up" href="#" style="display: none;"></a>
    <div class="layout">
        <?php echo $content ?>
    </div>

    <?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
