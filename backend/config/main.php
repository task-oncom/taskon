<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
	'language' => 'ru-RU',
	'defaultRoute' => '/main/main-admin/index',
    'modules' => [
		'content' => ['class' => 'common\modules\content\Module'],
		'faq' => ['class' => 'common\modules\faq\Module'],
		'reviews' => ['class' => 'common\modules\reviews\Module'],
		'users' => ['class' => 'common\modules\users\users'],
		'testings' => ['class' => 'common\modules\testings\Module'],
		'main' => ['class' => 'common\modules\main\main'],
		'rbac' => ['class' => 'common\modules\rbac\rbac'],
	],
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
		'session' => [
			'class' => 'yii\web\Session',
		],
		'dater' => [
			'class' => '\common\components\DaterComponent',
		],
		'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'locale' => 'ru-RU',
			'timeZone' => 'Europe/Moscow',
		],
		'i18n' => [
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\PhpMessageSource'
				],
			],
		], 
        'user' => [
            'identityClass' => 'common\modules\users\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'assetManager'=>[
			'linkAssets' => true,
            'bundles' => [
				'yii\bootstrap\BootstrapPluginAsset' => [
					'js' => [
					],
				],
				'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                         'plugins/jquery/jquery-1.9.1.min.js',
                    ],
					'jsOptions' => [
						'position' => \yii\web\View::POS_HEAD,
					],
                ],
				'yii\jui\JuiAsset' => [
                    'sourcePath' => null,
                    'js' => [
                         'plugins/jquery-ui/ui/minified/jquery-ui.min.js',
						 'plugins/jquery-ui/ui/minified/jquery.ui.widget.min.js',
                    ],
					'jsOptions' => [
						'position' => \yii\web\View::POS_HEAD,
					],
					'css' => [],
                ],
				'yii\bootstrap\BootstrapAsset' => [
				   'css' => [],
				]
            ]
        ],
		'authManager' => [
            'class' => 'yii\rbac\DbManager',
            /*'connectionID' => 'db',
            'itemTable' => 'auth_items',
            'assignmentTable' => 'auth_assignments',
            'itemChildTable' => 'auth_item_child',*/
            'defaultRoles' => [
                'user',
                'moderator',
                'admin',
                'superadmin'
            ],
        ],
		'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'enableStrictParsing' => true,
            'rules' => [
				'<module_id>/settings/manage'		=> 'settings/manage',
				'<module_id>/settings/create'		=> 'settings/create',
				'<module_id>/settings/update/<id>'	=> 'settings/update',
				'<module_id>/settings/delete/<id>'	=> 'settings/delete',
				'<module_id>/settings/view/<id>'	=> 'settings/view',
				
				'/'					=> 'site/index',
                '' 					=> 'site/index',
				
				'users/user-admin/manage/is_deleted/<is_deleted>' => 'users/user-admin/manage',
				
				'<_m>/<_c>/<_a>/<id>' => '<_m>/<_c>/<_a>',
				'<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',
				'<_c>/<_a>' => '<_c>/<_a>',
            ]
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
        ],
    ],
    'params' => $params,
];
