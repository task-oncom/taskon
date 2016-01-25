<?php

namespace common\modules\testings\components;

use yii\helpers\Json;

class MarkBoxAction extends \yii\base\Action
{
	public function run($session)
    {
    	if(isset($_POST['reset']) && $_POST['reset'])
    	{
    		\Yii::$app->controller->setMarked($session, []);

			echo Json::encode(array(
	            'qty' => 0,
	            'title' => "Разослать выделенным",
	        )); 
	        return;
    	}

        if(!isset($_POST['data']) || !is_array($_POST['data']))
        {
			return;
        }

		$toggle = $_POST['data'];
		
		$data = \Yii::$app->controller->getMarked($session);
		
		$remove = []; $append = [];

		foreach($toggle as $key => $value) 
        {
			if($value) 
            {
				$append[] = $key;
            }
			else
            {
				$remove[] = $key;
            }
		}
		if(!empty($append))
        {
			$data = array_merge($data, $append);
        }
      
        $data = array_unique($data);
			
		if(!empty($remove))
        {
			$data = array_diff($data, $remove);
        }
			
		\Yii::$app->controller->setMarked($session, $data);
        $qty = count($data);

		echo Json::encode([
            'qty' => $qty,
            'title' => "Разослать выделенным ($qty)",
        ]);
    }
}