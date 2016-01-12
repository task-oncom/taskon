<?php
use common\modules\rbac\models\AuthItem;
use common\modules\users\models\User;
use yii\helpers\ArrayHelper;

$roles = AuthItem::find(
	['type' => AuthItem::TYPE_ROLE],
	"name != '" . AuthItem::ROLE_GUEST . "'"
)->all();


return [
    'activeForm'=>[
        'id' => 'user-form',
        'class' => 'ActiveForm',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
//			'template' => '<div class="form-group">{label}<div class="col-md-9">{input}</div><div class="col-md-9">{error}</div></div>',
			'labelOptions' => ['class' => 'col-md-3 control-label'],
		],
        'enableAjaxValidation' => false,
// 	'htmlOptions'=>['class'=>'registr'),
    ],
    'elements'       => [
        'send_email' => ['type' => 'checkbox'],
        'email'      => ['type' => 'text', 'class' => 'form-control'],
        'name'		 => ['type' => 'text', 'class' => 'form-control'],
        'surname'		 => ['type' => 'text', 'class' => 'form-control'],
        'post'      => ['type' => 'text', 'class' => 'form-control'],
        'phone'      => ['type' => 'text', 'class' => 'form-control'],
        'mobile_phone'      => ['type' => 'text', 'class' => 'form-control'],
        'skype'      => ['type' => 'text', 'class' => 'form-control'],
        /*'status' => [
            'type'  => 'dropdownlist',
            'items' => User::$status_list,
			'class' => 'form-control',
    	],*/
        'status' => [
            'type' => 'checkbox',
            'value' => 'active',
            'template' => '{input}<a href="#" class="btn btn-xs btn-primary disabled m-l-5" data-id="switchery-state-text">true</a>{error}',
            'opts' => [
                'data-change' => 'check-switchery-state-text'
            ]
        ],
        'role' => [
            'type'  => 'dropdownlist',
            'items' => ArrayHelper::map($roles, 'name', 'description'),
			'class' => 'form-control',
    	],
        'password'   => ['type' => 'password', 'class' => 'form-control', 'pwd-id' => 'passwordStrengthDiv'],
        'password_c' => ['type' => 'password', 'class' => 'form-control', 'pwd-id' => 'passwordStrengthDiv2'],
        /*'captcha' => [
            'type' => 'captcha',
            'label' => 'Введите код с картинки',
			'class' => 'form-control'
        ],*/
    ],
    'buttons' => [
        'sp1' => ['type' => 'htmlBlock', 'value' => '<div class="col-md-8 col-sm-6" style="padding-left: 0;">',],
        'submit' => ['type' => 'submit', 'value' => 'Cохранить'],
        'sp11' => ['type' => 'htmlBlock', 'value' => '&nbsp;&nbsp;&nbsp;&nbsp;',],
        'block' => ['type' => 'info', 'value' => 'Заблокировать'],
        'sp12' => ['type' => 'htmlBlock', 'value' => '&nbsp;&nbsp;&nbsp;&nbsp;',],
        'delete' => ['type' => 'danger', 'value' => 'Удалить'],
        'sp2' => ['type' => 'htmlBlock', 'value' => '</div>',],

        'sp3' => ['type' => 'htmlBlock', 'value' => '<div class="col-md-4 col-sm-6" style="padding-right: 0;">',],
        'sp4' => ['type' => 'htmlBlock', 'value' => '<div class="widget widget-stats bg-blue">',],
        'sp5' => ['type' => 'htmlBlock', 'value' => '<span class=""><i class="icon-question"></i>&nbsp;&nbsp;&nbsp;<b>Заблокирован</b><br>Если пользователь заблокирован, то он не сможет войти в систему.<br>Данные внесенные пользователем не удаляются. </span><br><br>',],
        'sp6' => ['type' => 'htmlBlock', 'value' => '<span class=""><i class="icon-question"></i>&nbsp;&nbsp;&nbsp;<b>Удален</b><br>Внимание! Все данные пользователя введенные<br>под его именем будут безвозвратно удалены</span><br>',],
        'sp7' => ['type' => 'htmlBlock', 'value' => '</div>',],
        'sp8' => ['type' => 'htmlBlock', 'value' => '</div>',],
    ]
];


