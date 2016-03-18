<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(\yii::$app->controller->page_title) ?></title>
    <meta name="description" content="Панель управления Task On">

	<?php $this->head() ?>
    
    <?php
	$this->registerCssFile('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/plugins/bootstrap/css/bootstrap.min.css', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/plugins/font-awesome/css/font-awesome.min.css', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/css/animate.min.css', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/css/style.min.css', ['position' => \yii\web\View::POS_HEAD]);
    // $this->registerCssFile('/css/simple_line_icons.css', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/css/style-responsive.min.css', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerCssFile('/css/theme/default.css', ['position' => \yii\web\View::POS_HEAD]);
	?>
    <!-- ================== BEGIN BASE JS ================== -->
    <?php $this->registerJsFile('/plugins/pace/pace.min.js', ['position' => \yii\web\View::POS_HEAD ]);?>
	<!-- ================== END BASE JS ================== -->
</head>
<body>
    <?php $this->beginBody() ?>
    <!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<div class="login-cover">
	    <div class="login-cover-image"><img src="/img/login-bg/bg-1.jpg" data-id="login-cover-image" alt="" /></div>
	    <div class="login-cover-bg"></div>
	</div>
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
    <?=$content?>
    </div>

    <?php $this->endBody() ?>
    <?php
	
	$this->registerJsFile('/plugins/jquery/jquery-1.9.1.js', ['position' => \yii\web\View::POS_HEAD]);
	$this->registerJsFile('/plugins/jquery/jquery-migrate-1.1.0.js', ['position' => \yii\web\View::POS_HEAD ]);
	$this->registerJsFile('/plugins/jquery-ui/ui/minified/jquery-ui.min.js', ['position' => \yii\web\View::POS_HEAD ]);
	$this->registerJsFile('/plugins/bootstrap/js/bootstrap.min.js', ['position' => \yii\web\View::POS_HEAD ]);
	
	$this->registerJsFile('crossbrowserjs/html5shiv.js', ['position' => \yii\web\View::POS_END  ,'condition'=>'lt IE 9']);
	$this->registerJsFile('crossbrowserjs/respond.min.js', ['position' => \yii\web\View::POS_END  ,'condition'=>'lt IE 9']);
	$this->registerJsFile('crossbrowserjs/excanvas.min.js', ['position' => \yii\web\View::POS_END ,'condition'=>'lt IE 9']);
	
	$this->registerJsFile('/plugins/slimscroll/jquery.slimscroll.min.js', ['position' => \yii\web\View::POS_END ]);
	$this->registerJsFile('/plugins/jquery-cookie/jquery.cookie.js', ['position' => \yii\web\View::POS_END ]);
	?>

</body>
</html>
<?php $this->endPage() ?>
