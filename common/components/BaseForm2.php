<?php
namespace common\components;
use yii\widgets\ActiveForm;
use yii\base\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\captcha\Captcha;

class BaseForm extends View
{
    private $_clear = false;

    public $side;
	
	public $form;
	
	public $model;
	
	public $labelOptions = ['class' => 'col-md-3 control-label'];
	
	public $spsFields = array();

    public $cancel_button_show = true;

    /*public function __construct($config, $model = null, $parent = null)
    {
        if (\Yii::$app->controller instanceof AdminController)
        {
            $this->side = 'admin';
        }
        else
        {
            $this->side = 'client';
        }

        if (is_string($config))
        {
            //die(__DIR__);
			//$config = self::getFullAlias($config);
			$config = require(__DIR__ . "\..\.." . $config);
        }

        parent::__construct($config, $model, $parent);
        $this->addAttributesToButtons();
        $this->formatDateAttributes();
    }*/
	
	public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        return Html::beginForm($this->action, $this->method, $this->options);
    }
	
	public function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        if ($this->enableClientScript) {
            $id = $this->options['id'];
            $options = Json::encode($this->getClientOptions());
            $attributes = Json::encode($this->attributes);
            $view = $this->getView();
            ActiveFormAsset::register($view);
            $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        }

        return Html::endForm();
    }
	
	public function __construct($config, $model = null, $parent = null) {
		if (is_string($config))
        {
            //die(__DIR__);
			//$config = self::getFullAlias($config);
			$this->spsFields = require(__DIR__ . "\..\.." . $config . '.php');
        }
		else
		{
			$this->spsFields = $config;
		}
		$this->model = $model;
		if(!empty($config['activeForm']['fieldConfig']['labelOptions']))
			$this->labelOptions = $config['activeForm']['fieldConfig']['labelOptions'];
		return $this->renderForm();
	}
	
	public function renderForm() {
		$ret  = '<style>';
		$ret .= '.form-horizontal .form-group {margin-right: -5px; margin-left: 0;}';
		$ret .= '</style>';
		$ret .= '<div class="row">';
		$ret .= '<div class="col-md-9 ui-sortable">';
		$ret .= '<div class="panel panel-inverse" data-sortable-id="form-stuff-3">';
		$ret .= '<div class="panel-heading">';
		$ret .= '<div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>';
		$ret .= '<h4 class="panel-title">Default Style</h4>';
		$ret .= '</div>';
		$ret .= '<div class="panel-body">';
		$ret .= $this->form = ActiveForm::begin($this->spsFields['activeForm']);
//		echo '<fieldset>';
        foreach($this->spsFields['elements'] as $elem => $item) {
			$ret .= $this->renderElement($elem, $item);
		}
		foreach($this->spsFields['buttons'] as $elem => $item) {
			$ret .= $this->renderButton($elem, $item);
		}
//		echo '</fieldset>';
        $ret .= ActiveForm::end();
		$ret .= '</div>';
		$ret .= '</div>';
		$ret .= '</div>';
		$ret .= '</div>';
		$ret .= '<div class="col-md-6">';
		$ret .= '</div>';
		return $ret;
	}

	public function renderButton($elem, $item) {
		return '';
	}
	
	public function renderElement($elem, $item) {
		$options = [];
		$items = [];
		$ret = '';
		if(!empty($item['class'])) $options = ['class'=>$item['class']];
		if(!empty($item['items'])) $items = $item['items'];
		$field = $this->form->field($this->model, $elem);

		/*echo View::render('\common\components\views\elements\\'.$item['type'], ['field'=>$field, 'model'=>$this->model, 'elem'=>$elem, 'items'=>$items, 'options'=>$options]);*/
		
		switch($item['type']) {
			case 'checkbox':
				$ret .=  $field->begin();
				$ret .=  '<div class="form-group">';
				$ret .=  Html::activeLabel( $this->model, $elem, $this->labelOptions );
				$ret .=  '    <div class="col-md-9">';
				$ret .=  '        <div class="checkbox">';
//				echo '            <label class="col-md-3 control-label">';
								$ret .=  Html::activeCheckbox( $this->model, $elem, array_merge($options,['label'=>''])  );
								$ret .=  Html::error( $this->model, $elem);
//				echo '            </label>';
				$ret .=  '        </div>';
				$ret .=  '    </div>';
				$ret .=  '</div>';
				$ret .=  $field->end();
			break;
			case 'text':
				$ret .=  $field->begin();
				$ret .=  '<div class="form-group">';
				$ret .=  Html::activeLabel( $this->model, $elem, $this->labelOptions );
				$ret .=  '<div class="col-md-9">';
				$ret .=  Html::activeTextInput($this->model, $elem, $options );
				$ret .=  Html::error( $this->model, $elem);
				$ret .=  '</div>';
				$ret .=  '</div>';
				$ret .=  $field->end();
			break;
			case 'password':
				$ret .=  $field->begin();
				$ret .=  '<div class="form-group">';
				$ret .=  Html::activeLabel( $this->model, $elem, $this->labelOptions );
				$ret .=  '<div class="col-md-9">';
				$ret .=  Html::activePasswordInput($this->model, $elem, $options );
				$ret .=  Html::error( $this->model, $elem);
				$ret .=  '</div>';
				$ret .=  '</div>';
				$ret .=  $field->end();
			break;
			case 'dropdownlist':
			echo $field->begin();
				$ret .=  '<div class="form-group">';
				$ret .=  Html::activeLabel( $this->model, $elem, $this->labelOptions );
				$ret .=  '<div class="col-md-9">';
				$ret .=  Html::activeDropDownList( $this->model, $elem, $items, $options );
				$ret .=  Html::error( $this->model, $elem);
				$ret .=  '</div>';
				$ret .=  '</div>';
				$ret .=  $field->end();
			break;
			case 'captcha':
				$ret .=  '<div class="form-group">';
				$ret .=  Html::activeLabel( $this->model, $elem, $this->labelOptions );
				$ret .=  '<div class="col-md-9">';
				$ret .=  $this->form->field($this->model, $elem, ['template' => '{input}'])->widget(Captcha::className(), 
					[
						'captchaAction'=>Url::toRoute('/'.\Yii::$app->controller->module->id.'/'.\Yii::$app->controller->id.'/captcha'),
						'options'=>array_merge($options,['label' => '', 'style' => 'float: left; width:20%']),
						'template' => '{image} {input}'
					]);
				$ret .=  Html::error( $this->model, $elem);
				$ret .=  '</div>';
				$ret .=  '</div>';
			break;
		}
		return $ret;
	}
	
	public function renderButtons($elem, $item) {
		return '';
	}

//нужно еще добавить вывод городов

}
