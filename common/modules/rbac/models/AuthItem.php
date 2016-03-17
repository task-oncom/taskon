<?php
namespace common\modules\rbac\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\modules\rbac\models\AuthAssignment;

class AuthItem extends \common\components\ActiveRecordModel

{
	const PAGE_SIZE = 10;

	const PHOTOS_DIR = 'upload/news';

	const ROLE_DEFAULT = 'user';
	const ROLE_GUEST = 'guest';
	const ROLE_ROOT = 'admin';

	const TYPE_OPERATION = 0;
	const TYPE_TASK = 1;
	const TYPE_ROLE = 2;

	public $module;
	public $parent;
    public $id;
	public static $system_roles = array(
		self::ROLE_DEFAULT,
		self::ROLE_GUEST,
		self::ROLE_ROOT
	);

	public function name()
	{
		return 'Элементы авторизации';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function tableName()
	{
		return 'auth_item';
	}

	public function rules()
	{
		return array(
			array('name, description', 'required'),
			array('name', 'unique'),
			array(
				'name',
				'match',
				'pattern' => '/^[A-Za-z_]+$/ui',
				'message' => 'только латинский алфавит и нижнее подчеркивание'
			),
			array('type, allow_for_all', 'integer', 'integerOnly' => true),
			array('name', 'string', 'max' => 64),
			array('description, rule_name, data, created_at, updated_at', 'safe'),
			array('name', 'TypeUnique'),
			array('name, type, description, rule_name, data', 'safe', 'on' => 'search'),
		);
	}

	public function typeUnique($attr)
	{
		$exist = $this->findByAttributes(array('type' => $this->type, 'name' => $this->$attr));
		if($exist)
		{
			if($exist->primaryKey != $this->primaryKey)
			{
				$this->addError($attr, 'Данное имя уже занято!');
			}
		}
	}

	public function getAssignment() {
		return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
	}
	
	public function relations()
	{
		return array(
			'operations' => array(
				self::MANY_MANY,
				'AuthItem',
				'auth_items_childs(parent, child)',
				'condition' => 'type = "' . self::TYPE_OPERATION . '"'
			),
			'tasks' => array(
				self::MANY_MANY,
				'AuthItem',
				'auth_items_childs(parent, child)',
				'condition' => 'type = "' . self::TYPE_TASK . '"'
			),
			'users' => array(self::HAS_MANY, 'User', 'userid', 'through' => 'assignments')
		);
	}

	public function attributeLabels()
	{
		$labels = parent::attributeLabels();
		$labels['operations'] = 'Операции';
		$labels['tasks'] = 'Задачи';

		return $labels;
	}

	public function getTask()
	{
		static $task;

		if(!$task)
		{
			$subtable = AuthItemChild::model()->tableName();
			$sql = "SELECT * FROM {$this->tableName()}
							 WHERE name = (SELECT parent FROM {$subtable} WHERE child = '" . $this->name . "')";

			$task = $this->findBySql($sql);
		}
		return $task;
	}

	public function search($type)
	{
		$query = self::find();
		
		$dataProvider = new ActiveDataProvider([
			'query'  => $query,
			/*'pagination'=> [
				'pageSize'=> 2
			]*/
		]);
		

		$query->andFilterWhere(['name' => $this->name]);
		$query->andFilterWhere(['type' => $this->type]);
		$query->andFilterWhere(['description' => $this->description]);
		$query->andFilterWhere(['rule_name' => $this->rule_name]);
		$query->andFilterWhere(['data' => $this->data]);
		//$query->andFilterWhere(['type = ' => $type]);

		return $dataProvider;
	}

	public function getModulesWithActions()
	{
		$result = array();

		$items = AuthItem::model()->findAllByAttributes(array("type" => AuthItem::TYPE_OPERATION));

		$modules = AppManager::getModulesData(true);
		foreach($modules as $class => $data)
		{
			$actions = AppManager::getModuleActions($class);

			foreach($items as $item)
			{
				if(isset($actions[$item->name]))
				{
					unset($actions[$item->name]);
				}
			}

			if($actions)
			{
				$result[$class] = $data;
			}
		}

		return $result;
	}

	public static function constructName($controller_id, $action_id)
	{
		return ucfirst($controller_id) . '_' . ucfirst($action_id);
	}

	public function getRoles()
	{
		static $roles;
		if(!$roles)
		{
			$roles = $this->findAllByAttributes(array(
				//'type' => self::TYPE_ROLE
                'rule_name' => 'group'
			));
		}
		return $roles;
	}

	public function actionExists()
	{
		list($controller, $action) = explode('_', $this->name);

		$controller_class = $controller . 'Controller';
		$controller_file = $controller_class . '.php';

		$modules = \Yii::$app->getModules();

		foreach($modules as $class => $source)
		{
			
			//$class = new \ReflectionClass($source['class']);
			$module = \yii::$app->getModule($class);
			die(print_r($module->controllerMap[$class]));
			$controllers_path = $module->getControllerPath();
			if(!is_dir($controllers_path))
			{
				continue;
			}

			$controllers_files = scandir($controllers_path);

			if(in_array($controller_file, $controllers_files))
			{
				require_once $controllers_path . $controller_file;

				if(method_exists($controller_class, "action{$action}"))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}

}
