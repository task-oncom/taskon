<?php
namespace common\components\zii;
class AdminLinkPager extends \common\components\zii\LinkPager
{
    public $cssFile = '/css/yii/pager.css'; //надо бы в директорию admin перетащить

    public $lastPageLabel = 'Конец';
    public $firstPageLabel = 'Начало';
    public $prevPageLabel = '';
    public $nextPageLabel = '';

    public $header = '';

}