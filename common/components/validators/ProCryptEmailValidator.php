<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 01.05.2015
 * Time: 12:00
 */
namespace common\components\validators;

use yii\validators\EmailValidator;
use common\components\activeRecordBehaviors\ProCryptBehavior;

class ProCrypteMailValidator extends EmailValidator{

    protected function validateValue($value)
    {
        $src = ProCryptBehavior::$startFieldSource;

        if (strpos($value, $src) !== false) return true;
        // make sure string length is limited to avoid DOS attacks
        if (!is_string($value) || strlen($value) >= 320) {
            $valid = false;
        } elseif (!preg_match('/^(.*<?)(.*)@(.*?)(>?)$/', $value, $matches)) {
            $valid = false;
        } else {
            $domain = $matches[3];
            if ($this->enableIDN) {
                $value = $matches[1] . idn_to_ascii($matches[2]) . '@' . idn_to_ascii($domain) . $matches[4];
            }
            $valid = preg_match($this->pattern, $value) || $this->allowName && preg_match($this->fullPattern, $value);
            if ($valid && $this->checkDNS) {
                $valid = checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
            }
        }

        return $valid ? null : [$this->message, []];
    }
} 