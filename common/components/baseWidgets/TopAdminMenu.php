<?php

class TopAdminMenu extends \common\components\baseWidgets\Portlet
{
    const COUNT_MODULES_IN_MENU = 6;


    public function renderContent()
    {
        $modules = array();

        $pop_modules  = SiteAction::model()->getPopularModules(self::COUNT_MODULES_IN_MENU);
        $modules_data = AppManager::getModulesData(true, true);


        $session = $modules_data['SessionModule'];
        unset($modules_data['SessionModule']);
        array_unshift($modules_data, $session);
		if(Yii::app()->user->checkAccess('admin')) {
			$session = $modules_data['LoggerModule'];
			unset($modules_data['LoggerModule']);
			array_unshift($modules_data, $session);
		}
        if ($pop_modules)
        {

            foreach ($pop_modules as $module_name)
            {   
                if (isset($modules_data[$module_name])) 
                {
                    $modules[$module_name] = $modules_data[$module_name];
                }
            }

            if (count($modules) < self::COUNT_MODULES_IN_MENU)
            {
                foreach ($modules_data as $module_name => $data)
                {
                    if (isset($modules[$module_name]))
                    {
                        continue;
                    }

                    $modules[$module_name] = $data;

                    if (count($modules) == self::COUNT_MODULES_IN_MENU)
                    {
                        break;
                    }
                }
            }
        }
        else
        {
            $modules = array_slice($modules_data, 0, 6);
        }

        $this->render('TopAdminMenu', array('modules' => $modules));
    }
}
