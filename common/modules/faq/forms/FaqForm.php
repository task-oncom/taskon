<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 06.03.2015
 * Time: 20:11
 */

use yii\helpers\ArrayHelper;

return [
    'activeForm'=>[
        'id' => 'faq-form',
    ],
    'elements'       => [
        'lang'  => [
            'type' => 'dropdownlist',
            'class' => 'form-control',
            'items' => \yii\helpers\ArrayHelper::map(\common\modules\languages\models\Languages::find()->all(), 'code', 'name'),
            'empty' => 'Select Language',
        ],
        'name'    => [
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','First Name'),
            'title' => \yii::t('faq','First Name')
        ],
        'phone'    => [
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','Phone'),
            'title' => \yii::t('faq','Phone')
        ],
        'email'    => [
            'type' => 'email',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','Email'),
            'title' => \yii::t('faq','Email')
        ],
        'cat_id'  => [
            'type' => 'dropdownlist',
            'class' => 'form-control',
            'items' => \yii\helpers\ArrayHelper::map(\common\modules\faq\models\FaqCategory::find()->all(), 'id', 'name'),
            'empty' => 'Select Category',
        ],
        'question'    => [
            'type' => 'textarea',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','Question'),
            'title' => \yii::t('faq','Question')
        ],
        'answer'    => [
            'type' => 'textarea',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','Answer'),
            'title' => \yii::t('faq','Answer')
        ],
        'is_published'			=> ['type' => 'checkbox', 'readonly' => 'readonly'],
        /*'notification_date'    => [
            'type' => 'date',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','Notify Date'),
            'title' => \yii::t('faq','Notify Date'),
            'readonly' => 'readonly',
        ],
        'notification_send'	=> ['type' => 'checkbox'],*/
        'url'    => [
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => \yii::t('faq','Url'),
            'title' => \yii::t('faq','Url')
        ],
        /*'name'				=> ['type' => 'text', 'class' => 'form-control'],
        'activity_from'		=> ['type' => 'date', 'class' => 'form-control'],
        '<div style="clear: both;">',
        'activity_before'	=> ['type' => 'date', 'class' => 'form-control'],
        'enabled'			=> ['type' => 'checkbox'],
        'default'			=> ['type' => 'checkbox'],*/
    ],
    'buttons' => [
        'submit' => ['type' => 'submit', 'value' => 'Cохранить']
    ]
];