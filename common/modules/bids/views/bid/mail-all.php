<?php
use yii\helpers\Html;
use yii\helpers\Url;

use common\modules\bids\models\BidFile;
?>

Имя: <?=$model->name?><br>

Телефон: <?=$model->phone?><br>

Email: <?=$model->email?><br>

Сообщение: <?=$model->text?><br>

<?php if($model->files) : ?>
	<hr>
	Файлы: 
	<?php foreach ($model->files as $file) 
	{
		echo Html::a($file->filename,\Yii::$app->params['frontUrl'].BidFile::FILE_FOLDER.$file->filename) . '<br>';
	} ?>
	<hr>
<?php endif; ?>

Дата добавления заявки: <?=date('d.m.Y H:i:s', $model->created_at)?><br>


<?php if($session) : ?>

	<?php $last = $session->lastUrl; ?>

	Запрос отправлен со страницы: <a href="<?=Url::to([$last->url], true)?>"><?=Url::to([$last->url], true)?></a>

	<hr>

	<?php if($session->utmUrls) : ?>
		<h2>UTM:</h2>
		
		<?php foreach ($session->utmUrls as $url) : 
			$params = $url->parseUrl(); ?>
			
			<p>
				<strong>Дата посещения:</strong> <?=date('d.m.Y H:i:s', $url->created_at)?> <br>
				<strong>Время пребывания на посадочной странице:</strong> <?=date('H:i:s', mktime(0, 0, $url->time))?> <br>
				Источники: <?=$params['utm_source']?> <br>
				Маркетинговые каналы: <?=$params['utm_medium']?> <br>
				Ключевое слово в компании: <?=$params['utm_term']?> <br>
				Компания : <?=$params['campaign_id']?> <br>
			</p>

		<?php endforeach; ?>

	<?php else : ?>
		
		<h3>Данных UTM нет</h3>

	<?php endif; ?>

<?php endif; ?>