	<meta name="title" content="<?php echo \Yii::$app->controller->meta_title?>">
	<meta name="keywords" content="<?php echo \Yii::$app->controller->meta_keywords?>">
	<meta name="description" content="<?php echo \Yii::$app->controller->meta_description?>">

	<link rel="shortcut icon" href="/images/favicon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="/images/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-touch-icon-114x114.png">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<?php $this->registerCssFile('/css/animate.css');?>
	<?php $this->registerCssFile('https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');?>

	<?php $this->registerCssFile('/js/libs/bootstrap/css/bootstrap.css');?>
	<?php $this->registerCssFile('/js/libs/magnific/magnific-popup.css');?>
	<?php $this->registerCssFile('/js/libs/bxslider/jquery.bxslider.css');?>

	<?php $this->registerCssFile('/css/fonts.css');?>
	<?php $this->registerCssFile('/css/screen.css');?>
	<?php $this->registerCssFile('/css/media.css');?>

	<?php $this->registerJsFile('/js/modernizr.js', ['position' => yii\web\View::POS_HEAD ]);?>