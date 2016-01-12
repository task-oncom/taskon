<?php
namespace common\components;
class UrlManager extends \yii\web\UrlManager {

    public function parseRequest($request){
        $pages = \common\modules\content\models\CoContent::find([])->all();
        $rules = [];
        foreach($pages as $page) {
            //$rules[$page->url] = \yii\helpers\Url::toRoute(['/content/page/view','id' => $page->id]);
            $rules['<page:('.$page->url.')>'] = \yii\helpers\Url::toRoute(['/content/page/view']);
        }
        $this->addRules($rules, false);
        //die(print_r($request->getPathInfo()));
        return parent::parseRequest($request);
    }

}
