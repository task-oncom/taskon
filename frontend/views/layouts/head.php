    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="title" content="<?php echo \yii::$app->controller->meta_title?>">
	<meta name="keywords" content="<?php echo \yii::$app->controller->meta_keywords?>">
	<meta name="description" content="<?php echo \yii::$app->controller->meta_description?>">
    <meta name="author" content="LTD «Art Project»">

    <?php $this->registerCssFile('/css/jquery.autocomplete.css', ['position' => yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/style.css', ['position' => yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/site.css', ['position' => yii\web\View::POS_HEAD ]);?>
    <?php $this->registerCssFile('/css/fix.css', ['position' => yii\web\View::POS_HEAD ]);?>
