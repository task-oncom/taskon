<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\content\models\CoBlocks */

$this->title = Yii::t('content', 'Update Co Blocks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Co Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-blocks-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
