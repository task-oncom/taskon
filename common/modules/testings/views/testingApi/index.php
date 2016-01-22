<h2>Общая логика работы при выгрузке в сервис unisender</h2>
<ol>
	<li><a href = "<?=Yii::app()->createUrl('testings/TestingApi/list');?>">Создать новый список для выгрузки (если хотите создать абсолютно чистый список для рассылки email адресов)</a></li>
	<li><a href = "<?=Yii::app()->createUrl('testings/TestingApi/prepair');?>">Сделать выгрузку в выбранный список</a></li>
	<li>Перейти в личный кабинет в <a target = "_blank" href = "http://unisender.com">unisender.com</a></li>
	<li>Сформировать в системе unisender.com письмо для рассылки с тегами</li>
</ol>