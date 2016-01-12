<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'ru-RU',
    'defaultRoute' => 'content/page/view',
    'modules' => [
        'content' => ['class' => 'common\modules\content\Module',],
        'reviews' => ['class' => 'common\modules\reviews\Module',],
        'request' => ['class' => 'common\modules\request\Module',],
	    'faq' => ['class' => 'common\modules\faq\Module'],
        'main' => ['class' => 'common\modules\main\main'],
        'scoring' => ['class' => 'common\modules\scoring\Scoring',],
        'paysys' => ['class' => 'common\modules\paysys\Module',],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\modules\scoring\models\ScClient',
            'loginUrl' => ['/site/login'],
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'timeout'=>3600,
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
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru-RU',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager'=>[
            'linkAssets' => true,
            'bundles' => [
                'yii\web\YiiAsset' => [
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                    ],
                ],
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        'js/jquery-2.1.3.js',
                        'js/jquery.range.js',
                        'js/jquery.datepicker.js',
                        'js/jquery.tooltipster.js',
                        'js/jquery.formstyler.js',
                        'js/jquery.fancybox.js',
                        //'js/jquery.mask.js',
                        'js/masked-input.js',
                        'js/scripts.js',
                        'js/accounting.js'
                    ],
                    'jsOptions' => [
                        //'position' => \yii\web\View::POS_HEAD,
                        'position' => \yii\web\View::POS_BEGIN,
                    ],
                ],
                'yii\jui\JuiAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        //'plugins/jquery-ui/ui/minified/jquery-ui.min.js',
                        //'plugins/jquery-ui/ui/minified/jquery.ui.widget.min.js',
                    ],
                    'jsOptions' => [
                        //'position' => \yii\web\View::POS_BEGIN,
                    ],
                    'css' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ]
            ]
        ],
        'urlManager' => [
            'class' => 'common\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '' => 'content/page/view',

                '<page:(/)>' => 'content/page/view',
                'reviews' => 'reviews/review/index',
                'faq/view/all' => 'faq/faq/all',
                'faq/<url>' => 'faq/faq/view',
                'faq' => 'faq/faq/index',

                '<_m>/<_c>/<_a>/<id:\d+>' => '<_m>/<_c>/<_a>',
                '<_m>/<_c>/<_a>/<page>' => '<_m>/<_c>/<_a>',
                '<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',

                '<_c>/<_a>' => '<_c>/<_a>',

                //'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            /*'transport' => [
                'plugins' => [
                    [
                        'class' => 'Swift_Plugins_ThrottlerPlugin',
                        'constructArgs' => [20],
                    ],
                ],
            ],*/
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
