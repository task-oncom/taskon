<?php

namespace common\assets;

class JqueryCookieAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/jquery-cookie/src';
    public $js = [
        'jquery.cookie.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}