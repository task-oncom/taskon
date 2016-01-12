<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
?>
<!-- begin header navigation right -->

<ul class="nav navbar-nav navbar-right">
    <?php if(!\yii::$app->user->isGuest):?>
    <li class="dropdown navbar-user">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
            <!--img src="/img/user-13.jpg" alt="" /--> 
            <span class="hidden-xs"><?php echo Yii::$app->user->identity->email?></span> <b class="caret"></b>
        </a>
        <ul class="dropdown-menu animated fadeInLeft">
            <li class="arrow"></li>
            <!--li><a href="javascript:;">Edit Profile</a></li>
            <li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
            <li><a href="javascript:;">Calendar</a></li-->
            <li><a href="javascript:;">Настройки</a></li>
            <li class="divider"></li>
            <li><a data-method="post" href="/site/logout">Выход</a></li>
        </ul>
    </li>
    <?php endif?>
</ul>


<!-- end header navigation right -->