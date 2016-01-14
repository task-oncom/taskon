<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

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

        <nav class="menu">
            <div class="toggle_block"><a href="#" class="toggle-mnu"><span></span></a></div>
            <div class="phone_menu">8 800 123 45 67</div>
            <ul>
                <li><a href="/about" class="link">О компании</a></li>
                <li><a href="#" class="link">Кейсы</a></li>
                <li><a href="#" class="link">Школа аналитики</a></li>
                <li><a href="#" class="link">Контакты</a></li>
            </ul>
        </nav>

        <header>
            <div class="container">
                <div class="row">
                    <div class="col-md-2 col-xs-3 col-sm-12">
                        <a href="/" class="logo">
                            <img src="images/logo.png" height="34" width="125" alt="">
                        </a>
                    </div>
                    <div class="col-md-7 col-xs-6 col-sm-12">
                        <nav class="top_nav clearfix">
                            <ul>
                                <li><a href="/about">О компании</a></li>
                                <li><a href="#">Кейсы</a></li>
                                <li><a href="#">Школа аналитики</a></li>
                                <li><a href="#">Контакты</a></li>
                            </ul>
                        </nav>
                        <div class="lang_check">
                            <a href="#" class="d_menu"><i class="icon-arrowDown"></i>ENG</a>
                            <a href="#" class="d_menu_hide"><i class="icon-arrowRight"></i>RUS</a>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-3 col-sm-12">
                        <span class="top_phone">8 800 123 45 67</span>
                        <div class="phone_hover_head">Стоимость звонка 0 руб,<br/> в том числе с мобильного</div>
                    </div>
                </div>
            </div>
        </header>

        <?php echo $content ?>


    <?php $this->endBody() ?>

    <?php echo $this->render('foot')?>

</body>
</html>
<?php $this->endPage() ?>
