<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/bootstrap-datepicker/css/datepicker3.css',
        'plugins/bootstrap-datetimepicker/css/datetimepicker.css',
        'plugins/switchery/switchery.min.css',
        'plugins/powerange/powerange.min.css',
        'plugins/jquery-tag-it/css/jquery.tagit.css',
        'css/site.css',
    ];
    public $js = [
        'plugins/jquery-ui/ui/minified/jquery-ui.min.js',
        'plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
        'plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.ru.js',
        'plugins/tinymce/js/tinymce/tinymce.min.js',
        'js/form-load.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'backend\assets\HeadAsset',
    ];
}
