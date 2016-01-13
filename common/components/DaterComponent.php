<?php
/**
 * Created by JetBrains PhpStorm.
 * User: artos
 * Date: 14.09.11
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */
namespace common\components; 
class DaterComponent extends \yii\base\Component
{
    const PATTERN_DB_DATE_TIME   = '#^(\d\d\d\d)-(\d\d?)-(\d\d?) (\d\d)\:(\d\d):(\d\d)$#';
    const PATTERN_DB_DATE        = '#^(\d\d\d\d)-(\d\d?)-(\d\d?)$#';
    const PATTERN_FORM_DATE_TIME = '#^(\d\d?)\.(\d\d?)\.(\d\d\d\d) (\d\d)\:(\d\d):(\d\d)$#';
    const PATTERN_FORM_DATE      = '#^(\d\d?)\.(\d\d?)\.(\d\d\d\d)$#';


    public function formFormat($value)
    {
        if (preg_match(self::PATTERN_DB_DATE, $value))
        {
            $value = \Yii::$app->formatter->format('dd.MM.yyyy', $value);
        }
        else if (preg_match(self::PATTERN_DB_DATE_TIME, $value))
        {
            //TODO: написать код.
        }

        return $value;
    }


    public function readableFormat($value)
    {
        if (preg_match(self::PATTERN_DB_DATE, $value))
        {
            $value = \Yii::$app->formatter->format( $value, ['date','dd.MM.yyyy']);
        }
        else if (preg_match(self::PATTERN_DB_DATE_TIME, $value))
        {
            $value = \Yii::$app->formatter->asDate($value, 'long'/*, 'short'*/);
        }

        return $value;
    }


    public function isDbDate($value)
    {
        if (is_object($value) || is_array($value))
        {
            return false;
        }

        return preg_match(self::PATTERN_DB_DATE, $value) || preg_match(self::PATTERN_DB_DATE_TIME, $value);
    }
}
