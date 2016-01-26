<header class="header">

    <div class="top-sidebar">
        <div class="container-fluid">

            <button class="nav-mob-open"></button><!-- /nav-mob-open -->

            <div class="row">
                <div class="col-sm-3 col-md-4">


                        <div class="logo">
                            <a href="/">
                                <img src="/images/logo.png" alt="СОЦЗАЙМ">
                                <p class="logo-text">Онлайн выдача займов</p><!-- /logo-text -->
                            </a>
                        </div><!-- /logo -->


                </div><!-- /col-sm-4 -->
                <?php if(\yii::$app->user->isGuest):?>
                    <div class="col-sm-4 col-md-2 hidden-xs">


                    </div><!-- /col-sm-4 col-md-2 -->
                <?php endif?>

                <?php if(\yii::$app->user->isGuest):?>
                    <div class="col-md-3 hidden-xs hidden-sm">
                        <?php echo \common\modules\content\models\CoBlocks::printStaticBlock('auth')?>

                    </div><!-- /col-md-3 -->
                    <div class="col-md-3 hidden-xs hidden-sm">
                        <p class="phone"><a href="tel:<?=\common\components\AppManager::getSettingsParam('content-phone');?>"><?=\common\components\AppManager::getSettingsParam('content-phone');?></a><small>Звоните круглосуточно</small></p><!-- /phone -->

                    </div><!-- /col-md-3 -->
                <?php else:?>
                    <div class="col-sm-9 col-md-8 hidden-xs">
                        <?php echo \common\modules\content\models\CoBlocks::printStaticBlock('auth')?>
                        <p class="phone-small">
                            <?=\common\components\AppManager::getSettingsParam('content-phone');?>
                            <small>
                                <a href="#" class="btn-help"><span class="icon-help"></span>Задать вопрос с сайта</a><!-- /btn-help -->
                            </small>
                        </p><!-- /phone-small -->

                    </div><!-- /col-md-3 -->
                <?php endif?>
            </div><!-- /row -->
        </div><!-- /container-fluid -->
    </div><!-- /top-sidebar -->

    <nav class="nav-mob">
        <div class="nav-mob-line"></div><!-- /nav-mob-line -->
        <div class="nav-mob-overlay"></div><!-- /nav-mob-overlay -->
        <div class="nav-mob-content">
            <button class="nav-mob-close"></button><!-- /nav-mob-close -->
            <p class="phone"><a href="tel:88006820219"><?=\common\components\AppManager::getSettingsParam('content-phone');?></a></p><!-- /phone -->
            <div class="nav-mob-scroll">
                <ul class="nav-mob-list">
                    <li><a href="#"><span class="icon-auth"></span>Вход в личный кабинет</a></li>
                    <li><a href="#">О компании</a></li>
                    <li class="is-active"><a href="#">Как это работает</a></li>
                    <li><a href="#">Отзывы заемщиков</a></li>
                    <li><a href="#">Вопрос-ответ</a></li>
                    <li><a href="#">Взять займ</a></li>
                </ul><!-- /nav-mob-list -->
            </div><!-- /nav-mob-scroll -->
        </div><!-- /nav-mob-content -->
    </nav><!-- /nav-mob -->


    <?php echo $this->render('navmenu')?>
    <?php echo \common\modules\content\models\CoBlocks::printBlock('home')?>

</header><!-- /header -->