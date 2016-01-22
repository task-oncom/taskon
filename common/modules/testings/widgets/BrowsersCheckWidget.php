<?php

class BrowsersCheckWidget extends Portlet
{
    const BROWSER_COOKIE = 'test_browser_cookie';

    public $model;
    
    public function renderContent() 
    {
        if(!Yii::app()->request->cookies[self::BROWSER_COOKIE]->value && $browser = $this->check())
        {
            $cookie = new CHttpCookie(self::BROWSER_COOKIE, 1);
            Yii::app()->request->cookies[self::BROWSER_COOKIE] = $cookie;

            $this->render('BrowsersCheckWidget', compact('browser')); 
        }        
    }

    private function check()
    {
        $browser = new Browser();

        if($browser->getBrowser() == Browser::BROWSER_CHROME)
        {
            return false;
        }
        else
        {
            return $browser;
        }
    }
}
