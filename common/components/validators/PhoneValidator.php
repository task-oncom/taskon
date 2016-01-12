<?php

class PhoneValidator extends CValidator
{
    const PATTERN_PHONE = '/^\+?[1-9]{1}-[0-9]{3,5}-[0-9]{5,7}$/'; // Please, take a look at pattern! +?, {3,5}, {5,7}...
    const PHONE_LENGTH_WITH_PLUS = 14;
    const PHONE_LENGTH_WITHOUT_PLUS = 13;
    const PHONE_LENGTH_MIN = 10;
    const PHONE_LENGTH_MAX = 15;

    protected function validateAttribute($object, $attribute)
    {
        if (!empty($object->$attribute))
        {
            $phone = $object->$attribute;
            if (!preg_match(self::PATTERN_PHONE, $phone) || (strlen($phone)<self::PHONE_LENGTH_MIN || strlen($phone)>self::PHONE_LENGTH_MAX))
            {
                $this->addError($object, $attribute, Yii::t('main', 'Неверный формат! Пример: +7-495-1234567'));
            }
        }
    }
}
