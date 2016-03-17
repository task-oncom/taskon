<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\content\models\CoContent */

$this->title = Yii::t('content', 'Update Co Content');
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Co Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="co-content-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>