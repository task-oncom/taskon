<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\PostTag */

$this->title = 'Create Post Tag';
$this->params['breadcrumbs'][] = ['label' => 'Post Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-tag-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
