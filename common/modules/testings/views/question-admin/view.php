<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\modules\testings\models\Question;

/* @var $this yii\web\View */

?>

<div class="faq-view">

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('content', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('content', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
            	'attribute' => 'test.session.name',
            	'value' => Html::a($model->test->session->name, ['/testings/session-admin/view', 'id' => $model->test->session_id]),
            	'format' => 'html'
            ],
            [
            	'attribute' => 'test.name',
            	'value' => Html::a($model->test->name, ['/testings/test-admin/view', 'id' => $model->test_id]),
            	'format' => 'html'
            ],
            [
            	'attribute' => 'theme.name',
            	'value' => Html::a($model->theme->name, ['/testings/theme-admin/view', 'id' => $model->theme_id]),
            	'format' => 'html'
            ],
            'text',
            [
            	'attribute' => 'is_active',
            	'value' => Question::$active_list[$model->is_active],
            ],
			'author',
			[
            	'attribute' => 'type',
            	'value' => Question::$type_list[$model->type],
            ],
			'create_date',
        ],
    ]) ?>

</div>