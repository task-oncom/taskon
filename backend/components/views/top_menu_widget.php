<?php
/**
 * @var TopMenuWidget $this
 */

$mid = $this->getController()->module->id;
$pid = Yii::app()->request->getParam('id');
?>
<nav>
	<div class="container_12">

		<ul class="top-menu">
			<!--
			<?foreach ($categories as $cat): ?>
				<li><?php echo CHtml::link($cat->name, "#"); ?></li>
			<?php endforeach; ?>
			-->
			<li class="<?= ($mid == 'content' && $pid == 3 ? 'active' : ''); ?>"><a href="/about">О компании</a></li>
			<li class="<?= ($mid == 'reviews' ? 'active' : ''); ?>"><a href="/reviews">Отзывы</a></li>
			<li class="<?= ($mid == 'content' && $pid == 4 ? 'active' : ''); ?> <?php if ($mid=='catalog'): ?>
	 			active 
			<?php endif ?>   "><a href="/uslugi">Все услуги</a></li>
			<li class="<?= ($mid == 'faq' ? 'active' : ''); ?>"><a href="/faq">Часто задаваемые вопросы  </a></li>
			<li class="<?= ($mid == 'content' && $pid == 2 ? 'active' : ''); ?>"><a href="/contacts">Контакты </a></li>
		</ul>

	</div>
</nav>

<?php
//echo Yii::app()->controller->module->id;

/*
$uri = $_SERVER['REQUEST_URI'];

if ($uri == '/about' || $uri == '/dostavka' || $uri == '/garantija-na-svetodiodnye-lampy') {
    $tm_active_id = 'p_about';
} elseif ($uri == '/catalog' || Yii::app()->controller->module->id == 'catalog' ||  in_array($uri, $cat_uris)) {
    $tm_active_id = 'p_catalog';
} elseif ($uri == '/service' || $uri == '/montazh' || $uri == '/proektirovanie-osvescheniya' || $uri == '/ready') {
    $tm_active_id = 'p_service';
} elseif ($uri == '/base' || $uri == '/documents' || $uri == '/faq' || $uri == '/kak-rabotaet-svetodiod' || $uri == '/ekonomija-ot-primenenija-svetodiodov') {
    $tm_active_id = 'p_base';
} elseif ($uri == '/feedback') {
    $tm_active_id = 'p_feedback';
}

echo '
<script>
    document.getElementById("'.$tm_active_id.'").className = "active_top_menu";
</script>

';
*/
?>