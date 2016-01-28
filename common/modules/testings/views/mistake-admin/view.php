<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\modules\testings\models\Mistake;

/* @var $this yii\web\View */

?>

<div class="faq-view">

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'passing' => $model->passing_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			'description',
			[
				'attribute' => 'is_expert_agreed',
            	'value' => Mistake::$state_list[$model->is_expert_agreed],
			],		
			'create_date',
        ],
    ]) ?>

</div>




<?php

// if ($model->files) {
// 	$this->widget('fileManager.portlets.FileList', array(
// 		'model' => $model,
// 		'tag' => 'files',
// 		'tagName' => 'div',
// 		'htmlOptions' => array(
// 			'class' => 'file-list',
// 			'style' => 'margin: 20px 10px 0 10px;'
// 		),
// 	));
// }