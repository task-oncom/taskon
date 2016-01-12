<?php

namespace common\components;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use \common\components\activeRecordBehaviors\NullValueBehavior;
use \common\components\activeRecordBehaviors\UserForeignKeyBehavior;
use \common\components\activeRecordBehaviors\UploadFileBehavior;
use \common\components\activeRecordBehaviors\DateFormatBehavior;
use \common\components\activeRecordBehaviors\MaxMinBehavior;
use \common\components\activeRecordBehaviors\ScopesBehavior;

abstract class ActiveRecordModel extends ActiveRecord
{
    const PATTERN_RULAT_ALPHA_SPACES = '/^[а-яa-z ]+$/ui';
    const PATTERN_RULAT_ALPHA = '/^[а-яa-z]+$/ui';
    const PATTERN_LAT_ALPHA = '/^[A-Za-z]+$/ui';
    const PATTERN_PHONE = '/^[()+1-9-][0-9-]{5,}$/';
//    const PATTERN_PHONE = '/^\+[1-9]-[0-9]+-[0-9]{7}$/';
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';


    abstract public function name();


    /*public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }*/

    public function behaviors()
    {
        return array(
            'NullValue'      => [
                'class' => '\common\components\activeRecordBehaviors\NullValueBehavior'
            ],
            'UserForeignKey' => [
                'class' => '\common\components\activeRecordBehaviors\UserForeignKeyBehavior'
            ],
            'UploadFile'     => [
                'class' => '\common\components\activeRecordBehaviors\UploadFileBehavior'
            ],
            'DateFormat'     => [
                'class' => '\common\components\activeRecordBehaviors\DateFormatBehavior'
            ],
            'Timestamp'      => [
                'class' => '\yii\behaviors\TimestampBehavior',
            ],
            'MaxMin'         => [
                'class' => '\common\components\activeRecordBehaviors\MaxMinBehavior'
            ],
            'Scopes'         => [
                'class' => '\common\components\activeRecordBehaviors\ScopesBehavior'
            ],
        );
    }


    public function unsetAttributes($names=null)
	{
		if($names===null)
			$names=$this->attributes;
		foreach($names as $name)
			$this->$name=null;
	}
	
	public function attributeLabels()
    {
        $meta = $this->meta();

        $labels = array();

        foreach ($meta as $field_data)
        {
            //$labels[$field_data["Field"]] = Yii::t('main', $field_data["Comment"]);
			$labels[$field_data["Field"]] = $field_data["Comment"];
        }

        return $labels;
    }


    public function __get($name)
    {
        try
        {
            return parent::__get($name);
        } catch (CException $e)
        {
            $method_name = StringHelper::underscoreToCamelcase($name);
            $method_name = 'get' . ucfirst($method_name);

            if (method_exists($this, $method_name))
            {
                return $this->$method_name();
            }
            else
            {
                throw new CException($e->getMessage());
            }
        }
    }


    public function __set($name, $val)
    {
        try
        {
            return parent::__set($name, $val);
        } catch (CException $e)
        {
            $method_name = StringHelper::underscoreToCamelcase($name);
            $method_name = 'set' . ucfirst($method_name);

            if (method_exists($this, $method_name))
            {
                return $this->$method_name($val);
            }
            else
            {
                throw new CException($e->getMessage());
            }
        }
    }


    public function __toString()
    {
        $attributes = array(
            'name', 'title', 'description', 'id'
        );

        foreach ($attributes as $attribute)
        {
            if (array_key_exists($attribute, $this->attributes))
            {
                return $this->$attribute;
            }
        }
    }



 public static function numberFormat($number,$dec = 0,$th="",$br = " ")
  {
    $number = number_format($number,$dec,$th,$br);
    return str_replace(" ", "&nbsp;", $number);
  }

    /*___________________________________________________________________________________*/


    /*SCOPES_____________________________________________________________________________*/
    public function scopes()
    {
        $alias = $this->getTableAlias();
        return array(
            'published' => array('condition' => $alias . '.is_published = 1'),
            'ordered'   => array('order' => $alias . '.`order` DESC'),
            'last'      => array('order' => $alias . '.date_create DESC')
        );
    }


    public function limit($num)
    {
        $this->getDbCriteria()->mergeWith(array(
            'limit' => $num,
        ));

        return $this;
    }


    public function offset($num)
    {
        $this->getDbCriteria()->mergeWith(array(
            'offset' => $num,
        ));

        return $this;
    }


    public function notEqual($param, $value)
    {
        $alias = $this->getTableAlias();
        $this->getDbCriteria()->mergeWith(array(
            'condition' => $alias . ".`{$param}` != '{$value}'",
        ));

        return $this;
    }


    public function meta()
    {
        $cache_var = 'Meta_' . $this->tableName();

        $meta = \Yii::$app->cache->get($cache_var);
        if ($meta === false)
        {
            $meta = \Yii::$app->db->createCommand("SHOW FUll columns FROM " . $this->tableName())->queryAll();

            foreach ($meta as $ind => $field_data)
            {
                $meta[$field_data["Field"]] = $field_data;
                unset($meta[$ind]);
            }

            \Yii::$app->cache->set($cache_var, $meta, 3600);
        }

        return $meta;
    }


    public function optionsTree($name = 'name', $id = null, $result = array(), $value = 'id', $spaces = 0, $parent_id = null)
    {
        $objects = $this->findAllByAttributes(array(
            'parent_id' => $parent_id
        ));

        foreach ($objects as $object)
        {
            if ($object->id == $id)
            {
                continue;
            }

            $result[$object->$value] = str_repeat("_", $spaces) . $object->$name;

            if ($object->childs)
            {
                $spaces += 2;

                $result = $this->optionsTree($name, $id, $result, $value, $spaces, $object->id);
            }
        }

        return $result;
    }


    public function authObject()
    {
        $object_ids = AuthObject::model()->getObjectsIds(get_class($this), Yii::app()->user->role);

        $criteria = $this->getDbCriteria();
        $criteria->addInCondition('id', $object_ids);
        return $this;
    }



    /*VALIDATORS________________________________________________________________________________*/
    public function city($attr)
    {
        $name = trim($this->$attr);

        if (!empty($name))
        {
            if (!is_numeric($name))
            {
                $city = City::model()->findByAttributes(array('name' => $name));
                if ($city)
                {
                    $this->$attr = $city->id;
                }
                else
                {
                    $this->addError($attr, Yii::t('main', 'Город не найден'));
                }
            }
        }
        else
        {
            $this->$attr = null;
        }
    }


    public function phone($attr)
    {
        if (!empty($this->$attr))
        {
            if (!preg_match(self::PATTERN_PHONE, $this->$attr) OR $this->$attr == 123456)
            {
                $this->addError($attr, Yii::t('main', 'Укажите номер телефона с кодом города'));
            }
        }
    }


    public function latAlpha($attr)
    {
        if (!empty($this->$attr))
        {
            if (!preg_match(self::PATTERN_LAT_ALPHA, $this->$attr))
            {
                $this->addError($attr, Yii::t('main', 'Только латинский алфавит'));
            }
        }
    }


    public function ruLatAlpha($attr)
    {
        if (!empty($this->$attr))
        {
            if (!preg_match(self::PATTERN_RULAT_ALPHA, $this->$attr))
            {
                $this->addError($attr, Yii::t('main', 'Только русский или латинский алфавит, без пробелов'));
            }
        }
    }


    public function ruLatAlphaSpaces($attr)
    {
        if (!empty($this->$attr))
        {
            if (!preg_match(self::PATTERN_RULAT_ALPHA_SPACES, $this->$attr))
            {
                $this->addError($attr, Yii::t('main', 'Только русский или латинский алфавит с учетом пробелов'));
            }
        }
    }



public static function num2str($num,$kopeiki=false,$rubl=false) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',    1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    if($rubl)
    $out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    if($kopeiki)
    $out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
public static function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}



}


