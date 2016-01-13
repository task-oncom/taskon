<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 01.05.2015
 * Time: 12:43
 */

namespace common\components\validators;
use yii\validators\DateValidator;
use common\components\activeRecordBehaviors\ProCryptBehavior;

class ProCryptDateValidator extends DateValidator{
    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $src = ProCryptBehavior::$startFieldSource;

        if (strpos($value, $src) !== false) return true;
        return $this->parseDateValue($value) === false ? [$this->message, []] : null;
    }
} 