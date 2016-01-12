<?php
/**
 * ETranslitFilter class file reworked to Loco version: LocoTranslitFilter.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com> then almix
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace common\components\validators;
use \yii\validators\Validator;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;
use yii\helpers\Json;

class TransliterateValidator extends Validator
{
    /**
     * @var string the name of the attribute to be translit.
     */
    public $translitAttribute;
    /**
     * @var boolean whether to translit value only when the attribute value is null or empty string.
     * Defaults to true. If false, the attribute will always be translit.
     */
    public $setOnEmpty=false;

    public function init() {
        parent::init();
    }

    /**
     * Validates the attribute of the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    public function validateAttribute($object, $attribute)
    {
        if($this->setOnEmpty && !$this->isEmpty($object->$attribute))
            return;

        if(!$object->hasAttribute($this->translitAttribute))
            throw new InvalidConfigException(Yii::t('yiiext','Active record "{class}" is trying to select an invalid column "{column}". Note, the column must exist in the table or be an expression with alias.',
                array('{class}'=>get_class($object),'{column}'=>$this->translitAttribute)));

        $url = self::cyrillicToLatin($object->getAttribute($this->translitAttribute));
        $url = $this->checkUrl($object, $attribute, $url);
        $object->$attribute=$url;
        //die();
    }

    protected function checkUrl($object, $attribute, $url, $count = null) {
        $class = $object::className();
        $attrExist = $class::findAll([$attribute => $url]);
        if(count($attrExist)) {
            if(empty($count)) $count = 1;
            else {
                $count++;
                $url = substr($url, 0, strlen($url)-2);
            }
            $url .= "-$count";
            //echo $url.'<br>';
            $url = $this->checkUrl($object, $attribute, $url, $count);
        }
        return $url;
    }

    /**
     * @
     */
    public function clientValidateAttribute($model, $attribute, $view) {
        $label = $model->getAttributeLabel($attribute);
        $options = [
            'message' => \Yii::$app->getI18n()->format($this->message, [
                'attribute' => $label,
            ], \Yii::$app->language),
        ];
        return 'yii.validation.url(value, messages, ' . Json::encode($options) . ');';
    }
    /**
     * Translit text from cyrillic to latin letters.
     * @static
     * @param string $text the text being translit.
     * @return string
     */
    protected static function cyrillicToLatin($text, $toLowCase = TRUE)
    {
        $matrix=array(
            "й"=>"i","ц"=>"c","у"=>"u","к"=>"k","е"=>"e","н"=>"n",
            "г"=>"g","ш"=>"sh","щ"=>"shch","з"=>"z","х"=>"h","ъ"=>"",
            "ф"=>"f","ы"=>"y","в"=>"v","а"=>"a","п"=>"p","р"=>"r",
            "о"=>"o","л"=>"l","д"=>"d","ж"=>"zh","э"=>"e","ё"=>"e",
            "я"=>"ya","ч"=>"ch","с"=>"s","м"=>"m","и"=>"i","т"=>"t",
            "ь"=>"","б"=>"b","ю"=>"yu",
            "Й"=>"I","Ц"=>"C","У"=>"U","К"=>"K","Е"=>"E","Н"=>"N",
            "Г"=>"G","Ш"=>"SH","Щ"=>"SHCH","З"=>"Z","Х"=>"X","Ъ"=>"",
            "Ф"=>"F","Ы"=>"Y","В"=>"V","А"=>"A","П"=>"P","Р"=>"R",
            "О"=>"O","Л"=>"L","Д"=>"D","Ж"=>"ZH","Э"=>"E","Ё"=>"E",
            "Я"=>"YA","Ч"=>"CH","С"=>"S","М"=>"M","И"=>"I","Т"=>"T",
            "Ь"=>"","Б"=>"B","Ю"=>"YU",
            "«"=>"","»"=>""," "=>"-",

            "\""=>"", "\."=>"", "–"=>"-", "\,"=>"", "\("=>"", "\)"=>"",
            "\?"=>"", "\!"=>"", "\:"=>"",

            '#' => '', '№' => '',' - '=>'-', '/'=>'-', '  '=>'-',
        );

        // Enforce the maximum component length
        $maxlength = 100;
        $text = implode(array_slice(explode('<br>',wordwrap(trim(strip_tags(html_entity_decode($text))),$maxlength,'<br>',false)),0,1));
        //$text = substr(, 0, $maxlength);

        foreach($matrix as $from=>$to)
            $text=mb_eregi_replace($from,$to,$text);

// Optionally convert to lower case.
        if ($toLowCase)
        {
            $text = strtolower($text);
        }

        return $text;
    }
}