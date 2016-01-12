<?php
namespace common\components;

class AppManager
{
    private static $_modules_client_menu;

    public static function RandomString($cnt = 10)
    {
        $characters ='0123456789';
        $randstring = '';
        for ($i = 0; $i < $cnt; $i++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }

    public static function prepareWidget($data) {
        if(\Yii::$app->controller->id == 'block-admin') return $data;
        $patternNumberList = '/(.*?)\[\{number list\:(.*?)\}\](.*?)/im';
        $patternDottedList = '/(.*?)\[\{dotted list\:(.*?)\}\](.*?)/im';
        $patternH1 = '/(.*?)\[\{headerH1\:(.*?)\}\](.*?)/im';
        $patternH2 = '/(.*?)\[\{headerH2\:(.*?)\}\](.*?)/im';
        $patternASpan = '/(.*?)\[\{a span\:(.*?)\}\](.*?)/im';
        $patternButtonSpan = '/(.*?)\[\{button span\:(.*?)\}\](.*?)/im';
        $patternTitleHead = '/(.*?)\[\{titleHead\:(.*?)\}\](.*?)/im';

        $data = preg_replace_callback($patternButtonSpan, function($m){
            $params = explode('|',$m[2]);
            $ret = $m[1] . '<button class="'.$params[0].'"><span>'.$params[1].'</span><span>'.$params[2].'</span></button>' . $m[3];
            return $ret;
        }, $data);

        $data = preg_replace_callback($patternASpan, function($m){
            $params = explode('|',$m[2]);
            $ret = $m[1] . '<a class="'.$params[0].'" href="'.$params[1].'"><span>'.$params[2].'</span><span>'.$params[3].'</span></a>' . $m[3];
            return $ret;
        }, $data);

        $data = preg_replace_callback($patternNumberList, function($m){
            $list = explode('|', $m[2]);
            $ret = $m[1] . '<ol>';
            foreach($list as $item) {
                $ret .= '<li>'.$item.'</li>';
            }
            $ret .= '</ol>'.$m[3];
            return $ret;
        }, $data);
        $data = preg_replace_callback($patternDottedList, function($m){
            $list = explode('|', $m[2]);
            $ret = $m[1] . '<ul>';
            foreach($list as $item) {
                $ret .= '<li>'.$item.'</li>';
            }
            $ret .= '</ul>'.$m[3];
            return $ret;
        }, $data);
        $data = preg_replace_callback($patternH1, function($m){
            return $m[1]. "<h1>".$m[2]."</h1>".$m[3];
        }, $data);
        $data = preg_replace_callback($patternH2, function($m){
            return $m[1]. "<h2>".$m[2]."</h2>".$m[3];
        }, $data);

        $data = preg_replace_callback($patternTitleHead, function($m){
            $list = explode('|', $m[2]);
            $liAdd = '';
            if(count($list) > 1) {
                $title = $list[0];
                unset($list[0]);
                foreach($list as $item) {
                    $ls = explode(':', $item);
                    $liAdd .= '<li><a href="'.$ls[0].'">'.$ls[1].'</a></li>';
                }
            }
            else {
                $title = $list;
            }
            $ret = $m[1].'<div class="main-panel">
                <div class="container-fluid">

                    <ul tag="ul" class="path"><li><a href="/">Главная</a></li>'.$liAdd.'<li>'.$title.'</li></ul>
                    <p class="main-heading">'.$title.'</p><!-- /main-heading -->

                </div><!-- /container-fluid -->
            </div>'.$m[3];
            return $ret;
        }, $data);
        return $data;
    }

	public static function getModulesList() {
		$modules = \Yii::$app->getModules();
		$allModules = [];
		foreach($modules as $module_id=>$data) {
			if($module_id != 'debug' && $module_id != 'utility' && $module_id != 'gii')
				$allModules[$module_id] = \Yii::$app->getModule($module_id)->name();
		}
		return $allModules;
	}
	
	public static function getAdminMenu() {
		$menu = [];
		$currUrl = \Yii::$app->request->url;
		$moduleId = '';


//		die(print_r(\Yii::$app->request->queryParams));
		$query = \Yii::$app->request->queryParams;
		if(!empty($query['module_id']))
			$moduleId = $query['module_id'];
		elseif(!empty(\Yii::$app->controller->module)) 
			$moduleId = \Yii::$app->controller->module->id;

        if($moduleId == 'users') $moduleId = 'rbac';
		
		//die(print_r($moduleId));
		foreach(\Yii::$app->getModules() as $moduleName=>$actions)
		if($moduleName != 'debug'){
			if(method_exists(\Yii::$app->getModule($moduleName), 'adminMenu' ))	{
				$name = \Yii::$app->getModule($moduleName)->name();
                if(!\yii::$app->authManager->checkAccess(\Yii::$app->user->id,$moduleName)) continue;
				$ico = '';
				if(method_exists(\Yii::$app->getModule($moduleName), 'getMenuIcons'))
					$ico = \Yii::$app->getModule($moduleName)->getMenuIcons();
				$menu[$name]['ico'] = $ico;
				if(\Yii::$app->getModule($moduleName)->id == $moduleId )
					if((\Yii::$app->request->url === '/' || \Yii::$app->request->url === '') && $moduleId == 'main')
                        $menu[$name]['isActive'] = false;
                    else
                        $menu[$name]['isActive'] = true;
				else
					$menu[$name]['isActive'] = false;
				foreach(\Yii::$app->getModule($moduleName)->adminMenu() as $alias=>$url) {
					$subMenu['alias'] = $alias;
					$subMenu['url'] = $url;
					if($url == $currUrl) {
						$subMenu['isActive'] = true;
						//$menu[$name]['isActive'] = true;
					}

					else $subMenu['isActive'] = false;
                    /*die(print_r($currUrl));*/
                    if($moduleId == 'rbac' && strpos($url, 'rbac')) $subMenu['isActive'] = true;
                    else if(!empty(\yii::$app->controller->action->id))
                        if(\yii::$app->controller->action->id == 'create' || \yii::$app->controller->action->id == 'update')
                            if(strpos( $url,$moduleId. '/' .\yii::$app->controller->id ) !== false)
                                $subMenu['isActive'] = true;
					$menu[$name]['menu'][] = $subMenu;	
				}
                if($name != 'Управление доступом' && $name != 'Настройки системы' && $name != 'Управление контентом') {
                    $subMenu['alias'] = 'Настройки';
                    $subMenu['url'] = \yii\helpers\Url::toRoute(['/settings/manage', 'module_id' => \Yii::$app->getModule($moduleName)->id]);
                    if($subMenu['url'] == $currUrl) {
                        $subMenu['isActive'] = true;
                        //$menu[$name]['isActive'] = true;
                    }
                    else $subMenu['isActive'] = false;
                    $menu[$name]['menu'][] = $subMenu;
                }


			}
		}
				
//		\yii\helpers\VarDumper::dump($menu,10,true);die();
		return $menu;
	}
    
	public static function getModulesData($active = null, $check_allowed_links = false)
    {
        $modules = array();

        $modules_dirs = scandir(MODULES_PATH);
        foreach ($modules_dirs as $ind => $module_dir)
        {
            if ($module_dir[0] == '.')
            {
                continue;
            }

            $module_class = ucfirst($module_dir) . 'Module';
            $module_path  = MODULES_PATH . $module_dir . '/' . $module_class . '.php';

            if (!file_exists($module_path))
            {
                continue;
            }

            require_once $module_path;

            $vars = get_class_vars($module_class);

            if ($active !== null)
            {
                if (!array_key_exists('active', $vars))
                {
                    continue;
                }

                if ($active && !$vars['active'])
                {// If user is moderator - Start {
            $isModerator = Yii::app()->user->checkAccess('moderator');
            $moderatorAcceptedModules = array(
                'MainModule'=>array(
                    'admin_menu'=>array(),
                ),
                'FeedbackModule'=>array(),
                'CatalogModule'=>array(
                    'admin_menu'=>array(
                        'Заказы'=>'/catalog/ordersAdmin/manage'
                    ),
                ),
            );
            if ($isModerator && !in_array($module_class, $moderatorAcceptedModules))
            {
                continue;
            }
            // If user is moderator - End }
                    continue;
                }
            }

            $module = array(
                'description' => call_user_func(array($module_class, 'description')),
                'version'     => call_user_func(array($module_class, 'version')),
                'name'        => call_user_func(array($module_class, 'name')),
                'class'       => $module_class,
                'dir'         => $module_dir
            );

            if (method_exists($module_class, 'adminMenu'))
            {
                $module['admin_menu'] = call_user_func(array($module_class, 'adminMenu'));

                $settins_count = Setting::model()->count("module_id = '{$module_dir}'");
                if ($settins_count && !Yii::app()->user->checkAccess('manager'))
                {
                    $module['admin_menu']['Настройки'] = '/main/SettingAdmin/manage/module_id/' . $module_dir;
                }

                if ($check_allowed_links)
                {
                    foreach ($module['admin_menu'] as $title => $url)
                    {
                        $url = explode('/', trim($url, '/'));

                        if (count($url) < 3)
                        {
                            continue;
                        }

                        list($module_name, $controller, $action) = $url;

                        $auth_item = ucfirst($controller) . '_' . $action;
/*
                        if (!RbacModule::isAllow($auth_item))
                        {
                            unset($module['admin_menu'][$title]);
                        }*/
                    }
                }
            }

            // If user is moderator - Start {
            if (Yii::app()->user->checkAccess('moderator'))
            {
                $moderatorAcceptedModules = array(
                    'MainModule'=>array(
                        'admin_menu'=>array(),
                    ),
                    'FeedbackModule'=>array(),
                    'RecallModule'=>array(
                        'admin_menu'=>array(
                            'Управление'=>'/recall/recallAdmin/manage'
                        ),
                    ),
                );
                if (!array_key_exists($module_class, $moderatorAcceptedModules))
                {
                    continue;
                }
                else
                {
                    if(isset($moderatorAcceptedModules[$module_class]['admin_menu']))
                        $module['admin_menu'] = $moderatorAcceptedModules[$module_class]['admin_menu'];
                    $modules[$module_class] = $module;
                }
            }


            if (Yii::app()->user->checkAccess('manager'))
            {
                $managerAcceptedModules = array(
                    'MainModule'=>array(
                        'admin_menu'=>array(),
                    ),
                    'SessionModule'=>array(), 
                );
                if (!array_key_exists($module_class, $managerAcceptedModules))
                {
                    continue;
                }
                else
                {
                    if(isset($managerAcceptedModules[$module_class]['admin_menu']))
                        $module['admin_menu'] = $managerAcceptedModules[$module_class]['admin_menu'];
                    $modules[$module_class] = $module;
                }
            }


            // If user is manager - End }

            $modules[$module_class] = $module;
        }

        return $modules;
    }


    public function getModuleActions($module_class, $use_admin_prefix = false)
    {
        $actions = array();

        $controllers_dir = MODULES_PATH . lcfirst(str_replace('Module', '', $module_class)) . '/controllers/';

        $controllers = scandir($controllers_dir);
        foreach ($controllers as $controller)
        {
            if ($controller[0] == ".")
            {
                continue;
            }

            $class = str_replace('.php', '', $controller);
            if (!class_exists($class, false))
            {
                require_once $controllers_dir . $controller;
            }

            $reflection = new ReflectionClass($class);

            if (!in_array($reflection->getParentClass()->name, array('BaseController', 'AdminController')))
            {
                continue;
            }

            $actions_titles = call_user_func(array($class, 'actionsTitles'));

            $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method)
            {
                if (in_array($method->name, array('actionsTitles', 'actions')) ||
                    mb_substr($method->name, 0, 6, 'utf-8') != 'action'
                )
                {
                    continue;
                }

                $action = str_replace('action', '', $method->name);

                $action_name = str_replace('Controller', '', $class) . '_' . $action;

                $title = isset($actions_titles[$action]) ? $actions_titles[$action] : "";
                if ($title && $use_admin_prefix && strpos($action_name, "Admin_") !== false)
                {
                    $title .= " (ПУ)";
                }

                $actions[$action_name] = $title;
            }

        }

        return $actions;
    }


    public function getActionModule($action)
    {
        $controller_file = array_shift(explode("_", $action)) . "Controller.php";

        $modules_dirs = scandir(MODULES_PATH);
        foreach ($modules_dirs as $dir)
        {
            if ($dir[0] == ".")
            {
                continue;
            }

            $controllers = scandir(MODULES_PATH . "/" . $dir . "/controllers");

            if (in_array($controller_file, $controllers))
            {
                return ucfirst($dir) . "Module";
            }
        }
    }


    public static function getModuleName($id)
    {
        $module = Yii::app()->getModule($id);
        if ($module)
        {
            return $module->name();
        }
    }


    public static function getModels($params = array())
    {
        $result = array();

        $modules_dirs = scandir(MODULES_PATH);
        foreach ($modules_dirs as $module_dir)
        {
            if ($module_dir[0] == '.')
            {
                continue;
            }

            $module_class = ucfirst($module_dir) . 'Module';

            if (array_key_exists('active', $params))
            {
                $active_attr = new ReflectionProperty($module_class, 'active');
                if ($active_attr->getValue() !== $params['active'])
                {
                    continue;
                }
            }

            $models_dir = MODULES_PATH . $module_dir . '/models';
            if (!file_exists($models_dir))
            {
                continue;
            }

            $models_files = scandir($models_dir);
            foreach ($models_files as $model_file)
            {
                if ($model_file[0] == '.')
                {
                    continue;
                }

                $model_class = str_replace('.php', null, $model_file);
                $class       = new ReflectionClass($model_class);
                if ($class->isSubclassOf('ActiveRecordModel'))
                {
                    $model = ActiveRecordModel::model($model_class);
                }
                else
                {
                    continue;
                }

                if (isset($params['meta_tags']))
                {
                    $behaviors = $model->behaviors();
                    $behaviors = ArrayHelper::extract($behaviors, 'class');

                    if (!in_array('application.components.activeRecordBehaviors.MetaTagBehavior', $behaviors))
                    {
                        continue;
                    }
                }

                $result[$model_class] = $model->name();
            }
        }

        return $result;
    }


    public static function getModulesClientMenu()
    {
        if (!self::$_modules_client_menu)
        {

            $modules = self::getModulesData(true);

            foreach ($modules as $module)
            {
                if (method_exists($module['class'], 'clientMenu'))
                {
                    $client_menu = call_user_func(array($module['class'], 'clientMenu'));
                    if (is_array($client_menu))
                    {
                        $modules_urls = array_merge($modules_urls, $client_menu);
                    }
                }
            }

            self::$_modules_client_menu = array_flip($modules_urls);
        }

        return self::$_modules_client_menu;
    }
	
	public static function getSettingsParam($param, $module_id = null) {
		$fnd = ['code'=>$param];
        if(!empty($module_id)) $fnd['module_id'] = $module_id;
        $ret = \common\models\Settings::findOne($fnd);
		if(!empty($ret)) return $ret->value;
		return 'не определено';
	}

    public static function setSEO($modifier = '') {
        if(empty(\yii::$app->controller->module)) return false;
        $title = self::getSettingsParam('title'.$modifier, \yii::$app->controller->module->id);
        if($title == 'не определено') $title = \yii::$app->controller->module->id;
        $keywords = self::getSettingsParam('keywords'.$modifier, \yii::$app->controller->module->id);
        if($keywords == 'не определено') $keywords = \yii::$app->controller->module->id;
        $description = self::getSettingsParam('description'.$modifier, \yii::$app->controller->module->id);
        if($description == 'не определено') $description = \yii::$app->controller->module->id;

        \yii::$app->controller->meta_title = $title;
        \yii::$app->controller->meta_keywords = $keywords;
        \yii::$app->controller->meta_description = $description;
    }

    /**
     * Возвращает сумму прописью
     * @author runcore
     * @uses morph(...)
     */
    public static function num2str($num, $isval = false) {
        $nul='ноль';
        $ten=array(
            array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
            array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
        );
        $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
        $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
        $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
        $unit=array( // Units
            array('копейка' ,'копейки' ,'копеек',	 1),
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
        $out[] = self::morph(intval($rub), $isval?$unit[1][0]:'',$isval?$unit[1][1]:'',$isval?$unit[1][2]:''); // rub
        if((int)$kop > 0) $out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
        else $out[] = '';
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

    static public function cyrillicToLatin($text, $maxLength, $toLowCase)
    {
        $dictionary = array(
            'й' => 'i',
            'ц' => 'c',
            'у' => 'u',
            'к' => 'k',
            'е' => 'e',
            'н' => 'n',
            'г' => 'g',
            'ш' => 'sh',
            'щ' => 'shch',
            'з' => 'z',
            'х' => 'h',
            'ъ' => '',
            'ф' => 'f',
            'ы' => 'y',
            'в' => 'v',
            'а' => 'a',
            'п' => 'p',
            'р' => 'r',
            'о' => 'o',
            'л' => 'l',
            'д' => 'd',
            'ж' => 'zh',
            'э' => 'e',
            'ё' => 'e',
            'я' => 'ya',
            'ч' => 'ch',
            'с' => 's',
            'м' => 'm',
            'и' => 'i',
            'т' => 't',
            'ь' => '',
            'б' => 'b',
            'ю' => 'yu',

            'Й' => 'I',
            'Ц' => 'C',
            'У' => 'U',
            'К' => 'K',
            'Е' => 'E',
            'Н' => 'N',
            'Г' => 'G',
            'Ш' => 'SH',
            'Щ' => 'SHCH',
            'З' => 'Z',
            'Х' => 'X',
            'Ъ' => '',
            'Ф' => 'F',
            'Ы' => 'Y',
            'В' => 'V',
            'А' => 'A',
            'П' => 'P',
            'Р' => 'R',
            'О' => 'O',
            'Л' => 'L',
            'Д' => 'D',
            'Ж' => 'ZH',
            'Э' => 'E',
            'Ё' => 'E',
            'Я' => 'YA',
            'Ч' => 'CH',
            'С' => 'S',
            'М' => 'M',
            'И' => 'I',
            'Т' => 'T',
            'Ь' => '',
            'Б' => 'B',
            'Ю' => 'YU',

            '\-' => '-',
            '\s' => '-',

            '[^a-zA-Z0-9\-]' => '',

            '[-]{2,}' => '-',
        );
        foreach ($dictionary as $from => $to)
        {
            $text = mb_ereg_replace($from, $to, $text);
        }

        $text = mb_substr($text, 0, $maxLength, \Yii::$app->charset);
        if ($toLowCase)
        {
            $text = mb_strtolower($text, \Yii::$app->charset);
        }

        return trim($text, '-');
    }

    public static function getECP() {
        return 'asdlkj323mfl23rfds9ufjslk';
    }
}





































