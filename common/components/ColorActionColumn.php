<?php

namespace common\components;

use Yii;
use yii\helpers\Html;
use yii\grid;


class ColorActionColumn extends \yii\grid\ActionColumn
{
    public $contentOptions = [
        'class' => 'color-column',
        'style' => 'white-space: nowrap;',
        'align' => 'center'
    ];

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                return Html::a('<i class="fa fa-eye fa-lg"></i>', $url, [
                    'title' => Yii::t('yii', 'View'),
                    'data-toggle' => 'tooltip',
                    'data-pjax' => '0',
                ]);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                return Html::a('<i class="fa fa-pencil fa-lg"></i>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-toggle' => 'tooltip',
                    'data-pjax' => '0',
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                return Html::a('<i class="fa fa-trash-o fa-lg"></i>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-toggle' => 'tooltip',
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);
            };
        }
    }

}
