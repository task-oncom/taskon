<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
//die('---');
/* @var $this \yii\web\View */
/* @var $content string */
//die('-!-!-');
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="ru">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title><?php echo \Yii::$app->controller->page_title?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
    <?= Html::csrfMetaTags() ?>
	
	<?php $this->head() ?>
    <?php echo $this->render('head')?>

</head>
<body>
	<?php $this->beginBody() ?>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="/" class="navbar-brand"><img src="/img/logo.png"></a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				
				<?php echo $this->render('header-menu');?>
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->
		
		<?php if(!(\yii::$app->controller->id == 'client' && \yii::$app->controller->action->id == 'index')):?>
        <!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<!--div class="image">
							<a href="javascript:;"><img src="/img/user-13.jpg" alt="" /></a>
						</div-->
						<div class="info">
							<?php echo \yii::$app->user->identity->name?>
							<small><?php echo \yii::$app->user->identity->getPost()?></small>
						</div>
					</li>
				</ul>
				<!-- end sidebar user -->
				<?php echo $this->render('left-menu')?>
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
    <?php endif?>
		
		<?php if(!empty(\Yii::$app->controller->tabs) && false):?>
        <!-- begin #content -->
				<div class="row pull-right">
                    <?php foreach (\Yii::$app->controller->tabs as $title => $href): ?>
                    <div class="col-md-3 col-sm-6">
                    	<div class="widget widget-stats bg-green" style="min-height: 70px;">
                        <a href="<?php echo $href; ?>"><?php echo $title; ?></a>
                        </div>
                    </div>
                    <?php $class = ""; ?>
                    <?php endforeach ?>
                </div> 
        <?php endif?>
        <div id="content" class="content">
		<?php
        echo \common\components\MyBreadcrumbs::widget();
        ?>
        <h1 class="page-header"><?php echo \yii::$app->controller->page_title?></h1>
        <div class="panel panel-inverse">
        	<div class="panel-heading">
                <div class="panel-heading-btn">
                    <!-- <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-question-circle"></i></a> -->
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" title="Развернуть/Свернуть окно" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <!--a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a-->
                </div>
                <h4 class="panel-title"> &nbsp; </h4>
            </div>
        	<?php if(\Yii::$app->session->hasFlash('error')):?>
            <div class="alert alert-info">
            	<button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">×</span>
                            </button>
                <p><i class="fa fa-file-o"></i>
                <pre>
                <?php print_r(\Yii::$app->session->getFlash('error'))?>
                </pre>
                </p>
                
            </div>
            <?php endif?>
            <div class="panel-body">
				<div class="table-responsive">
			<?php echo $content;?>
            	</div>
            </div>
        </div>

		</div>
		<!-- end #content -->
        <div class="footer" id="footer">
            &copy; 2009-2015 ООО «Арт Проект»
            <p>УВЕДОМЛЕНИЕ О КОНФИДЕНЦИАЛЬНОСТИ: Это веб-страница и любые документы доступные для скачивание на данной странице содержат конфиденциальную информацию. Настоящим уведомляем Вас о том, что если содержание на этой веб-странице не предназначены Вам, использование, копирование, распространение информации, содержащейся на данной веб-странице, а также осуществление любых действий на основе этой информации, строго запрещено. Если Вы по ошибке попали на данную веб-страницу, пожалуйста, сообщите об этом Администратору ресурса.</p>
        </div>
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>

	<!-- end page container -->
	<?php $this->endBody() ?>
	<?php echo $this->render('foot')?>
</body>
</html>
<?php $this->endPage() ?>