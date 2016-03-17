<?php
namespace common\components;

use common\modules\languages\models\Languages;
use common\modules\content\models\CoContent;

class UrlManager extends \yii\web\UrlManager {

    public function parseRequest($request)
    {
        $pages = CoContent::find()->where(['active' => true])->all();
        $rules = [];

        foreach($pages as $page) 
        {
            $rules['<page:('.$page->url.')>'] = 'content/page/view';
        }
        
        $this->addRules($rules, false);

        return parent::parseRequest($request);
    }

    public function createUrl($params)
    {
        if(isset($params['lang_id']))
        {
            //Если указан идентификатор языка, то делаем попытку найти язык в БД,
            //иначе работаем с языком по умолчанию
            $lang = Languages::findOne($params['lang_id']);
            if($lang === null)
            {
                $lang = Languages::getDefaultLang();
            }

            unset($params['lang_id']);
        } 
        else 
        {
            //Если не указан параметр языка, то работаем с текущим языком
            $lang = Languages::getCurrent();
        }
        
        //Получаем сформированный URL(без префикса идентификатора языка)
        $url = parent::createUrl($params);
        
        //Добавляем к URL префикс - буквенный идентификатор языка
        if($url == '/')
        {
            return '/' . (!$lang->default?$lang->url:'');
        }
        // Делаем универсальный URL без языка для Eauth авторизации
        elseif (strpos($url, '/eauth') !== false) {
            return $url;
        }
        else
        {
            return (!$lang->default?'/'.$lang->url:'') . $url;
        }
    }
}
