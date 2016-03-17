<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\languages\models\Languages */

$this->title = Yii::t('content', 'Create Languages');
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="languages-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
