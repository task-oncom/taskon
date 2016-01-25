<?php
use yii\helpers\ArrayHelper;

use common\modules\testings\models\User;
use common\modules\testings\models\Test;

return [
    'activeForm'=>[
        'id' => 'testing-answer-form',
    ],
    'elements' => [
        'user_id' => [
            'type' => 'dropdownlist',
            'items' => ArrayHelper::map(Question::find()->all(), 'id', 'fio'),
        ],
        'user_id' => [
            'type' => 'dropdownlist',
            'items' => ArrayHelper::map(Test::find()->all(), 'id','name', 'session.name'),
        ],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];

