<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\modules\reviews\models\Reviews;

return [
    'activeForm'=>[
        'id' => 'review-form',
		'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ],
    'elements'       => [
        'title'              => [
            'type' => 'text',
        ],
        ($model->photo?Html::img(\Yii::$app->params['frontUrl'] . $model->photo):''),
        'unlinkFile'       => ['type' => 'checkbox', 'class' => 'form-control',],
        'file'              => ['type' => 'file', 'class' => 'form-control',],
        'video'              => ['type' => 'text', 'class' => 'form-control',],
        'date'				=> ['type' => 'date', 'class' => 'form-control',],
        'text'				=> ['type' => 'textarea', 'class' => 'form-control'],
        // '<a id="doAnswer" style="cursor: pointer;" onclick="$(this).next().next().slideToggle(); return false;">Ответить</a>' .
        // '<br><div style="display: none;">',
        // 'admin_id'              => [
        //     'type' => 'dropdownlist',
        //     'items' => ArrayHelper::map(\common\modules\users\models\User::find()->all(),'id','fio'),
        //     'empty' => 'Оператор',

        // ],
        // 'answer'			=> ['type' => 'textarea', 'class' => 'form-control'],
        // '</div>',
        'state'				=> [
            'type' => 'dropdownlist',
            'items' => ['active' => 'Активен', 'hidden' => 'Скрыт'],
            'class' => 'form-control'
        ],
        'priority' => ['type' => 'text', 'class' => 'form-control'],
        // 'rate_usability'			=> [
        //     'type' => 'dropdownlist',
        //     'items' => Reviews::getSource('rate_usability'),
        //     'class' => 'form-control'
        // ],
        // 'rate_loyality'				=> [
        //     'type' => 'dropdownlist',
        //     'items' => Reviews::getSource('rate_loyality'),
        //     'class' => 'form-control'
        // ],
        // 'rate_profit'				=> [
        //     'type' => 'dropdownlist',
        //     'items' => Reviews::getSource('rate_profit'),
        //     'class' => 'form-control'
        // ],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


