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
        <?= Html::a("Экспорт результатов", ['testings/testing-session-admin/export-session-result', 'id' => $model->id], ['class' => 'btn btn-info']) ?>        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'start_date',
            'end_date',
            'create_date',
        ],
    ]) ?>

    <?php echo '<br/><br/><h2>Продленные сессии</h2>'; ?>
	<?php foreach ($users as $time => $user)
	{
		echo $user->first_name.' '.$user->last_name.' '.$user->patronymic.' до '.$time.'<br/>';
	} ?>

</div>