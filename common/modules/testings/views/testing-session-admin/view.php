<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Список сессий', 'url' => ['/testings/testing-session-admin/manage']];
$this->params['breadcrumbs'][] = 'Сессия "' . $model->name;
?>

<div class="faq-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <!-- <?= Html::a(Yii::t('content', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('content', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?> -->
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