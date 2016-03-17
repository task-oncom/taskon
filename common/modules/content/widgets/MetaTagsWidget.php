<?php

namespace common\modules\content\widgets;

use Yii;
use yii\helpers\Html;

class MetaTagsWidget extends \yii\bootstrap\Widget
{
    public $form;

    public $model;
    
    public function run()
    {
        return $this->render('meta-tags', [
            'model' => $this->model,
            'form' => $this->form,
        ]);
    }
}
