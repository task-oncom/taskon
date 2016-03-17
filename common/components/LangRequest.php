<?php
namespace common\components;

use Yii;
use yii\web\Request;
// use yii\helpers\Url;
use common\modules\languages\models\Languages;

class LangRequest extends Request
{
    private $_lang_url;

    public function init()
    {
        $url = explode('/', $this->getUrl());
        $url = $url[1];
        $lang = Languages::getLangByUrl($url);

        if($lang !== null && $lang->default)
        {
            header('Location: ' . $this->getLangUrl()); die();
        }

        parent::init();
    }

    public function getLangUrl()
    {
        if ($this->_lang_url === null) 
        {
	        $this->_lang_url = $this->getUrl();
	        
	    	$url_list = explode('/', $this->_lang_url);

	    	$lang_url = isset($url_list[1]) ? $url_list[1] : null;

	    	Languages::setCurrent($lang_url);

            if( $lang_url !== null && $lang_url === Languages::getCurrent()->url && 
                strpos($this->_lang_url, Languages::getCurrent()->url) === 1)
            {
                $this->_lang_url = substr($this->_lang_url, strlen(Languages::getCurrent()->url) + 1);
            }
        }

        return $this->_lang_url;
    }

    protected function resolvePathInfo()
    {
        $pathInfo = $this->getLangUrl();

        if (($pos = strpos($pathInfo, '?')) !== false) 
        {
            $pathInfo = substr($pathInfo, 0, $pos);
        }

        $pathInfo = urldecode($pathInfo);

        // try to encode in UTF8 if not so
        // http://w3.org/International/questions/qa-forms-utf-8.html
        if (!preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $pathInfo)
        ) 
        {
            $pathInfo = utf8_encode($pathInfo);
        }

        $scriptUrl = $this->getScriptUrl();
        $baseUrl = $this->getBaseUrl();

        if (strpos($pathInfo, $scriptUrl) === 0) 
        {
            $pathInfo = substr($pathInfo, strlen($scriptUrl));
        } 
        elseif ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) 
        {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        } 
        elseif (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0) 
        {
            $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
        } 
        else 
        {
            throw new InvalidConfigException('Unable to determine the path info of the current request.');
        }

        if ($pathInfo[0] === '/') 
        {
            $pathInfo = substr($pathInfo, 1);
        }

        return (string) $pathInfo;
    }
}