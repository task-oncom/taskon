<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use common\modules\testings\models\User;

/* @var $this yii\web\View */
/* @var $model common\modules\faq\models\Faq */

?>

<div class="faq-view">

    <h1><?= Html::encode($model->fio) ?></h1>

    <p>
        <?= Html::a(Yii::t('content', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	[
				'attribute' => 'sex',
				'value' => User::$sex_list[$model->sex],
				'filter' => false
			],
			'first_name',
			'patronymic',
			'last_name',
			'company_name',
			'city',
			'login',
			'email:email',
			[
				'attribute' => 'manager_id',
				'value' => ($model->manager)?$model->manager->name:"Пользователь удалён"
			],
			'tki',
			'region',
			'create_date',
        ],
    ]) ?>

</div>

