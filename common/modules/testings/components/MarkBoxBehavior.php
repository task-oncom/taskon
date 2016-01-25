<?php

namespace common\modules\testings\components;

use Yii;
use yii\base\Behavior;

class MarkBoxBehavior extends Behavior
{
	public $session_key = 'notify_session';

	private $_marked;

	public function getMarked($session)
	{
		$session = intval($session);
		if(!isset($this->_marked[$session]))
		{
			$session_key = $this->session_key . '_' . $session;
			
			if(isset(Yii::$app->session[$session_key])) 
            {
				$data = unserialize(Yii::$app->session[$session_key]);

				if($data === FALSE)
                {
					$data = [];
                }
			} 
            else 
            {
				$data = [];
			}

			$this->_marked[$session] = $data;
		}
		
		return $this->_marked[$session];
	}
	
	public function setMarked($session, $data)
	{		
		$session = intval($session);
		$session_key = $this->session_key . '_' . $session;
		
		$this->_marked[$session] = $data;
		Yii::$app->session[$session_key] = serialize($data);
	}
	
	public function checkMark($data, $row) 
    {
		return in_array($data->id, $this->getMarked());
	}
}