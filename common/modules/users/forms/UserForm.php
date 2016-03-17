<?php

use \yii\helpers\ArrayHelper;

use common\modules\users\models\User;

$js = <<<JS
    visibleSupport();
    $('#user-role').change(function(){
        visibleSupport();
    });
    function visibleSupport()
    {
        if($('#user-role').val() == 'support')
        {
            $('#user-redmine_key').closest('.form-group').show();
        }
        else
        {
            $('#user-redmine_key').closest('.form-group').hide();
        }
    }
JS;
\Yii::$app->view->registerJs($js, \yii\web\View::POS_READY, 'SupportID');

return [
    'activeForm'=>[
        'id' => 'user-form',
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
            'items' => User::$role_list,
			'class' => 'form-control',
    	],
        'redmine_key' => [
            'type'  => 'text',
            'class' => 'form-control',
        ],
        'password'   => ['type' => 'password', 'class' => 'form-control', 'pwd-id' => 'passwordStrengthDiv'],
        'password_c' => ['type' => 'password', 'class' => 'form-control', 'pwd-id' => 'passwordStrengthDiv2'],
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


