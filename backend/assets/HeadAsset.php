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
class HeadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'plugins/switchery/switchery.min.js',
        'plugins/powerange/powerange.min.js',
        'plugins/pace/pace.min.js',
        'js/form-slider-switcher.demo.js',
        'js/packages/adminBaseClasses/buttonSet.js',
        'js/packages/adminBaseClasses/gridBase.js',
        'js/apps.min.js',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $depends = [

    ];
}
