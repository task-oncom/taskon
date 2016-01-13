<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\FaqCategory */

$this->title = Yii::t('content', 'Create Faq Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('content', 'Faq Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
