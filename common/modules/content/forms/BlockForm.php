<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
return [
    'activeForm'=>[
        'id' => 'block-form',
    ],
    'elements'       => [
		/*'lang' => [
			'type'  => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\languages\models\Languages::find()->all(), 'code', 'name'),
			'class' => 'form-control',
		],*/
        'lang' => [
            'type' => 'hidden',
        ],
        'category_id' => [
            'type'  => 'dropdownlist',
            'items' => ArrayHelper::map(\common\modules\content\models\CoCategory::find()->all(), 'id', 'name'),
            'empty' => 'Без привязки к категории',
            'class' => 'form-control',
            'hint' => 'Для того что бы было проще сортировать блоки в общем списке вы можете привязать ее к определенной категории. Список категории настраивается
' . Html::a('тут',['/content/category-admin/manage']) . '.',
        ],
		'title'				=> ['type' => 'text', 'class' => 'form-control'],
		'name'				=> ['type' => 'text', 'class' => 'form-control', 'hint' => '<b>Виджет</b> - элемент интерфейса предназначенный для формирования контента статическойи и динамической страницы.<br>Например, если необходимо на статическую или динамическую страницу встроить текстовый блок или любой другой элемент, то вы можете создать его в данном разделе. Для использования созданного виджета на странице достаточно будет указать в текстовом редакторе имя виджета заключенное в фигурных скобках. Например, {name-widget} – обозначает вставку контента созданного в виджете «name-widget».'],
		'text'				=> ['type' => 'textarea', 'class' => 'form-control'],
//		'active'			=> ['type' => 'checkbox'],
//		'created_at'				=> ['type' => 'text', 'class' => 'form-control'],
//		'updated_at'				=> ['type' => 'text', 'class' => 'form-control'],
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];


