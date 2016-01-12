<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$blocks = \common\modules\content\models\CoBlocks::find()->all();
$block_hint = '';
if(count($blocks)) {
    $block_hint .= "<ul>\n";
    foreach($blocks as $block) {
        $block_hint .= "<li>{{$block->name}} {$block->title}</li>\n";
    }

    $block_hint .=  "</ul>\n";
}

return [
    'activeForm'=>[
        'id' => 'controller-form',
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
		'category_id' => [
			'type'  => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\content\models\CoCategory::find()->all(), 'id', 'name'),
            'empty' => 'Без привязки к категории',
			'class' => 'form-control',
            'hint' => 'Для того что бы было проще сортировать данную страницу в общем списке вы можете привязать ее к определенной категории. Список категории настраивается
' . Html::a('тут',['/content/category-admin/manage']) . '.',
		],
		'url'				=> [
            'type' => 'text',
            'class' => 'form-control',
            'hint' => 'Для создания ЧПУ («Человеку Понятный Урл») укажите латинскими буквами путь, например, razdel/podrazdel/nazvanie_stranici.html ',
        ],
		'name'				=> [
            'type' => 'text', 
            'class' => 'form-control',
            'hint' => 'Название страницы не будет отображаться пользователям сайта и служит исключительно для служебного пользования в Панель управления.',
        ],
		'title'				=> [
            'type' => 'text', 
            'class' => 'form-control',
            'hint' => 'Заголовок страницы виден пользователю сайта и как правило оформляется в тег &lt;h1&gt;. Если дизайном страницы не предусмотрен вывод заголовка, то он не будет выводиться даже если был введен в данное поле.',
        ],
        'text'				=> [
            'type' => 'textarea',
            'class' => 'form-control',
            'label' => 'Текст редактируемый на странице<br>'.$block_hint,
        ],
		'active'			=> [
            'type' => 'checkbox',
            'template' => '{input}<a href="#" class="btn btn-xs btn-success m-l-5 disabled">Страница скрыта от пользователя / Страница видна пользователю</a>',
            'opts' => [
                'data-theme' => 'self',
                // 'data-secondary-color' => "#79C137",
                'data-render' => "switchery",
                'label' => ' ',
            ],
            
        ],
//		'created_at'				=> ['type' => 'text', 'class' => 'form-control'],
//		'updated_at'				=> ['type' => 'text', 'class' => 'form-control'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


