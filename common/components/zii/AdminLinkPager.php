<?php
namespace common\components\zii;
use common\components\zii\LinkPager;
class AdminLinkPager extends LinkPager
{
    public $cssFile = '/css/yii/pager.css'; //надо бы в директорию admin перетащить

    public $lastPageLabel = false;
    public $firstPageLabel = false;
    public $prevPageLabel = 'Назад';
    public $nextPageLabel = 'Вперед';
	public $activePageCssClass = 'current';

    public $header = '';

}