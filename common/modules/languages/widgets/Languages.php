<?php
namespace common\modules\languages\widgets;

use common\modules\languages\models\Languages as Model;

class Languages extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() 
    {
        return $this->render('languages', [
            'current' => Model::getCurrent(),
            'langs' => Model::find()->where('id != :current_id', [':current_id' => Model::getCurrent()->id])->all(),
            'count' => Model::find()->count(),
        ]);
    }
}