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
	    'faq' => ['class' => 'common\modules\faq\Module'],
        'bids' => ['class' => 'common\modules\bids\Module'],
        'blog' => ['class' => 'common\modules\blog\Module'],
        'sitemap' => [
            'class' => 'frontend\modules\sitemap\Sitemap',
            'models' => [
                // your models
                'common\modules\content\models\CoContent',
            ],
            'urls'=> [
                // // your additional urls
                // [
                //     'loc' => '/faq',
                //     'changefreq' => \himiklab\sitemap\behaviors\SitemapBehavior::CHANGEFREQ_DAILY,
                //     'priority' => 0.8,
                //     'faq' => [
                //         'publication'   => [
                //             'name'          => 'Вопрос-Ответ',
                //             'language'      => 'ru',
                //         ],
                //     ],
                // ],
            ],
            'enableGzip' => true, // default is false
            'cacheExpire' => 1, // 1 second. Default is 24 hours
        ],
    ],
    'components' => [
        'request' => [
            'class' => 'common\components\LangRequest'
        ],
        'view' => [
            'class' => '\mirocow\minify\View',
            'base_path' => '@app/web', // path alias to web base
            'minify_path' => '@app/web/minify', // path alias to save minify result
            'minify_css' => true,
            'minify_js' => true,
            'minify_html' => true,
            'js_len_to_minify' => 1000, // Больше этого размера inlinejs будет сжиматься и упаковываться в файл
            'force_charset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
            'expand_imports' => true, // whether to change @import on content
            //'css_linebreak_pos' => false,
        ],
        'user' => [
            'identityClass' => 'common\modules\users\models\User',
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
                        '/js/libs/jquery/jquery-1.11.2.min.js'
                    ],
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD,
                        // 'position' => \yii\web\View::POS_BEGIN,
                    ],
                ],
                'yii\jui\JuiAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        'plugins/jquery-ui/ui/minified/jquery-ui.min.js',
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
                ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],

                '' => 'content/page/view',

                '<page:(/)>' => 'content/page/view',
                'reviews' => 'reviews/review/index',
                'faq/view/all' => 'faq/faq/all',
                'faq/<url>' => 'faq/faq/view',
                'faq' => 'faq/faq/index',
                'blog' => 'blog/post/index',
                'blog/tag/<tag>' => 'blog/post/tag',
                'blog/<url>' => 'blog/post/view',

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
