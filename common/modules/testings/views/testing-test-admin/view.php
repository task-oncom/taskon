<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */

?>

<div class="faq-view">

    <h1><?= Html::encode($model->name) ?></h1>

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Импорт вопросов из CSV-файла', ['import-tests', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	[
				'attribute' => 'session.name',
				'value' => Html::a($model->session->name, array('/testings/testing-session-admin/view', 'id' => $model->session_id)),
				'format' => 'raw',
			],
            'name',
            'minutes',
            'questions',
            'pass_percent',
            'attempt',
            'create_date',
        ],
    ]) ?>

</div>