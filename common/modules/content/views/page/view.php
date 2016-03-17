<?php 

use common\modules\content\models\CoContent;

?>


<?php if($model->type == CoContent::TYPE_SIMPLE) : ?>
	<section class="simple-section <?=($model->custom==CoContent::CUSTOM_DARK?'dark':'')?>">
		<div class="container">
<?php endif; ?>

<?=$content;?>

<?php if($model->type == CoContent::TYPE_SIMPLE) : ?>
		</div>
	</section>

	<?=$this->render('@app/views/layouts/footer')?>
<?php endif; ?>