<?php
namespace common\components\activeRecordBehaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ProCryptBehavior extends Behavior {
    protected $_key = 'qwe123asd456zxc';
    protected $_iv;
    protected $_ivs;
    protected $keyPath = '/data';
    public static $startFieldSource = '$F==$f';
    public $enabledCryptStartFildSource = false;
    protected $startField = '';
    public $allowProCrypt = false;
    public $fields = [];

    public static function getStartField() {
        return self::$startFieldSource;
    }

    public function events() {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'InsertUpdate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'InsertUpdate',
            ActiveRecord::EVENT_AFTER_FIND => 'Find',
        ];
    }

    public function init() {
        parent::init();
        if($this->allowProCrypt) {
            if (!file_exists(yii::$app->basePath . '/..' . $this->keyPath . '/encryption.key')) {
                $iv = mcrypt_create_iv($this->_ivs = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC));
                file_put_contents(yii::$app->basePath . '/..' . $this->keyPath . '/encryption.key', serialize($iv));
            }
            else
                $iv = unserialize(file_get_contents(yii::$app->basePath . '/..' . $this->keyPath . '/encryption.key'));
            $this->_iv = $iv;
            $this->startField = $this->getStartField();
        }
    }

    /**
     *
     * Add coded property to strong
     *
     * @access    private
     * @param     string    $text
     * @return    string    The encrypted string
     *
     */
    private function addCodeStr($text) {
        return $this->startField . $text;
    }

    /**
     *
     * Remove coded property to strong
     *
     * @access    private
     * @param     string    $text
     * @return    string    The encrypted string
     *
     */
    private function removeCodeStr($text) {
        return str_replace($this->startField, '', $text);
    }

    /**
     *
     * Check coded property to strong
     *
     * @access    private
     * @param     string    $text
     * @return    boolean   The encrypted string
     *
     */
    private function checkCodeStr($text) {
        return str_replace($this->startField, '', $text) !== $text;
    }

    /**
     *
     * Encrypt a string
     *
     * @access    private
     * @param     string    $text
     * @return    string    The encrypted string
     *
     */
    private function _encrypt($text){

        if($this->allowProCrypt && $this->checkCodeStr($text) == false) {
            $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $this->_key, $text, MCRYPT_MODE_CBC, $this->_iv);
            return $this->addCodeStr(base64_encode($data));
        }
        else return $text;
    }

    /**
     *
     * Decrypt a string
     *
     * @access    private
     * @param    string    $text
     * @return    string    The decrypted string
     *
     */
    private function _decrypt($text, $system = false){
        if($this->allowProCrypt) {
            if(strlen($text) == 0 || ($this->checkCodeStr($text) == false && !$system))
                return $text;
            $text = base64_decode($this->removeCodeStr($text));

            return trim(mcrypt_decrypt(MCRYPT_BLOWFISH, $this->_key, $text, MCRYPT_MODE_CBC, $this->_iv));
        }
        else return $text;
    }

    public function InsertUpdate($event) {
        if($this->allowProCrypt && count($this->fields)) {
            foreach($this->fields as $field) {
                $this->owner->$field = $this->_encrypt($this->owner->$field);
            }
        }
        return true;
    }

    public function Find($event) {
        if($this->allowProCrypt && count($this->fields)) {
            foreach($this->fields as $field) {
                $this->owner->$field = $this->_decrypt($this->owner->$field);
            }
        }
        return true;
    }
}
