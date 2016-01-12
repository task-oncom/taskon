<?php

namespace backend\components\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ProfileDetailView extends \yii\widgets\DetailView
{
    public $header = [];
   
    public function run()
    {
        $rows = [];
        $i = 0;
        foreach ($this->attributes as $attribute) {
            $rows[] = $this->renderAttribute($attribute, $i++);
        }
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'table');

        $heads = [];
        
        if(isset($this->header))
        {
            foreach ($this->header as $row) 
            {
                $heads[] = '<tr><th></th><th>'.$row.'</th></tr>';
            }
        }

        echo Html::tag($tag, Html::tag('thead', implode("\n", $heads)) . Html::tag('tbody', implode("\n", $rows)), $options);
    }

    protected function renderAttribute($attribute, $index)
    {
        if (is_string($this->template)) {
            return strtr($this->template, [
                '{label}' => $attribute['label'],
                '{value}' => $this->formatter->format($attribute['value'], $attribute['format']),
                '{class}' => isset($attribute['class'])?$attribute['class']:'',
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }
}