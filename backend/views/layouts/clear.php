<?php

use yii\helpers\Html;

use backend\assets\AppAsset;

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
	<title>Произошла какая-то ошибка</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <?= Html::csrfMetaTags() ?>
	
	<?php $this->head() ?>
    <?php echo $this->render('head')?>

</head>
<body>
	<?php $this->beginBody() ?>

	<?=$content?>
	
	<?php $this->endBody() ?>

	<?php echo $this->render('foot')?>
</body>
</html>
<?php $this->endPage() ?>