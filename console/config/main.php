<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'gii' => 'yii\gii\Module',
		/*'rbac' => [
            'class' => 'common\modules\rbac\rbac',
        ],*/
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=mfo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
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
    ],
    'params' => $params,
];
