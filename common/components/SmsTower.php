<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 02.04.2015
 * Time: 19:03
 */
namespace common\components;
class SmsTower
{

    const login = 'u11061';
    const password = 'N0SjqP';
    //private $sender = '';
    const sender = 'SOC-ZAIM';

    private static function errorMessage($code)
    {
        $code = 1 * $code;
        $data = [
            0 => 'Ошибка CURL',
            1 => 'Success',
            2 => 'Ошибка: Некорректный номер телефона',
            3 => 'Ошибка: Некорректное смс сообщение',
            4 => 'Ошибка: Неправильный логин или пароль',
            5 => 'Ошибка: Недостаточно средств на балансе',
            6 => 'Ошибка: Некорректный отправитель',
            7 => 'Ошибка: Некорректный IP адрес',
            10 => 'Ошибка: Тех. проблема на стороне смс шлюза'
        ];
        return $data[$code];
    }

    private static function getUrl($code){
      $data  = [
        'send' => 'http://clients.smstower.ru/sender.v2.php',
        'status' => 'http://clients.smstower.ru/getstatus.v2.php',
        'balance' => 'https://clients.smstower.ru/getbalance.php'
      ];
        return $data[$code];
    }

    public function __construct() {

    }

    private static function makeRequest($type, $postData) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,self::getUrl($type));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $data = curl_exec($ch);
        //die(print_r($data));
        if (curl_errno($ch)) return self::errorMessage(0).curl_errno($ch);
        curl_close($ch);
        $xml = simplexml_load_string($data);

        if($xml->code[0] == 1) {
            //print_r($xml);
            $ret = [
                'status' => 'ok',
                'message' => self::errorMessage($xml->code[0]),
                'data' => $xml,
            ];
        }
        else {
            //print_r($data);
            $ret = [
                'status' => 'error',
                'message' => self::errorMessage($xml->code[0]),
                'data' => [],
            ];
        }

        return $ret;
    }

    public static function sendSMS($phone, $sms) {
        $data = "sender=".self::sender."&password=".self::password."&login=".self::login."&sms=".$sms."&phone=".$phone;
//die($data);
        $ret = self::makeRequest('send', $data);
        return $ret;
    }

    public static function getBalance() {
        $data = "password=".self::password."&login=".self::login;
        $ret = self::makeRequest('balance', $data);
        return $ret;
    }

    public static function getStatus($id) {
        $data = "password=".self::password."&login=".self::login."&messageid=".$id;
        $ret = self::makeRequest('status', $data);
        return $ret;
    }
}