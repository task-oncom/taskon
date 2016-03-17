<?php
namespace common\components;
use \yii\db\ActiveRecord;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

class BaseForm extends \yii\widgets\ActiveForm
{
    private $_clear = false;

    public $side;
	
	public $model;
	
	public $out;

    public $cancel_button_show = true;
	
	public function __construct($config, $model = null, $parent = null)
    {
        $this->model = $model;
        $behaviors = $model->behaviors();
        //die(print_r($behaviors));

        $metaFlags = false;
        if(!empty($behaviors['meta'])) $metaFlags = true;

        if($metaFlags)
            if(empty($model->metaTags) ) {
                $meta = new \common\models\MetaTags();
                $meta->language = 'ru';
            }
            else $meta = $model->metaTags;

		if (\yii::$app->controller instanceof AdminController)
        {
            $this->side = 'admin';
        }
        else
        {
            $this->side = 'client';
        }

        if (is_string($config))
        {
            $config = self::getFormConfig($config, $model);
        }
		//$config['ActiveForm'] = $config['activeForm'];
		//die(print_r($config['elements']));
		// unset($config['activeForm']);
		$config['options'] = [];
		//die(print_r(\yii::$app->controller->getAction('captcha')));
        //$config['options'][] = $this->errorSummary($model);
		foreach($config['elements'] as $key=>$element) {
			if(!is_array($element))
				$config['options'][] = $element;
			else {
                $opt = [];
                $optEl = [];
                if(!empty($element['empty']))
                    $opt['prompt'] = $element['empty'];
                if(!empty($element['data']))
                {
                    $opt['data'] = $element['data'];
                }
                if(!empty($element['placeholder']))
                    $opt['placeholder'] = $element['placeholder'];
                if(!empty($element['title']))
                    $opt['title'] = $element['title'];
                if(!empty($element['readonly']))
                    $opt['readonly'] = $element['readonly'];
                if(!empty($element['value']))
                    $opt['value'] = $element['value'];
                if(!empty($element['options']['label']))
                    $optEl['labelOptions'] = ['label' => $element['options']['label']];
                if(!empty($element['fileOptions']))
                    $opt = $element['fileOptions'];
                if(!empty($element['empty']))
                    $opt['value'] = $element['empty'];
                if(!empty($element['inputOptions']))
                    $optEl['inputOptions'] =  $element['inputOptions'];
                if(!empty($element['inputOptions']))
                    $opt['inputOptions'] =  $element['inputOptions'];
                switch($element['type']) {
                    case 'checkbox':
                        $opt['data-render'] = 'switchery';
                        $opt['data-theme'] = 'default';
                        $opt['data-classname'] = 'switchery';
                        if(!empty($element['opts']))
                            $opt = array_merge($opt, $element['opts']);
                        if(!empty($element['value'])) $opt['value'] = $element['value'];
                        $opts = [];
                        if(!empty($element['template']))
                            $opts = ['template' => $element['template']];
                        $config['options'][] = $this->field($this->model, $key, $opts)->checkbox(
                            $opt,
                            false
                        );
                    break;
                    case 'radio':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->radio($opt);
                    break;
                    case 'text':
                    case 'email':
                        $tmp = $this->field($this->model, $key, $optEl)->input($element['type'], $opt);
                        if(!empty($element['hint'])) $tmp = $tmp->hint($element['hint']);
                        if(array_key_exists('options', $element) && $element['options']['label'] === false) $tmp = $tmp->label(false);
                        $config['options'][] = $tmp;
                        break;
                    case 'password':
                        //$opt['value'] = '';
                        $optEl['template'] = '{label}{input}<div id="'.$element['pwd-id'].'" class="is0 m-t-5"></div>{error}';
                        $tmp = $this->field($this->model, $key, $optEl)->input($element['type'], $opt);
                        if($element['options']['label'] === false) $tmp = $tmp->label(false);
                        $config['options'][] = $tmp;
                    break;
                    case 'hidden':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->hiddenInput($opt)->label(false);
                    break;
                    case 'dropdownlist':
                        //$opt['data-live-search'] = 'true';
                        //$opt['data-size'] = 10;
                        //$opt['class'] = 'form-control selectpicker col-md-8';
                        //$opt['data-style']="btn-white";
                        //$optEl['template'] = '{label}<div class="col-md-8">{input}</div>{error}{hint}';
                        $tmp = $this->field($this->model, $key, $optEl)->dropDownList($element['items'],$opt);
                        if(!empty($element['hint'])) $tmp = $tmp->hint($element['hint']);
                        if(array_key_exists('options', $element) && $element['options']['label'] === false) $tmp = $tmp->label(false);
                        $config['options'][] = $tmp;
                    break;
                    case 'listBox':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->listBox($element['items'], $opt);
                    break;
                    case 'checkboxList':
                        $opt['data-render'] = 'switchery';
                        $opt['data-theme'] = 'default';
                        $opt['data-classname'] = 'switchery';
                        $config['options'][] = $this->field($this->model, $key, $optEl)->checkboxList(
                            $element['items'],
                            $opt
                        );
                    break;
                    case 'radioList':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->radioList($element['items'], $opt);
                    break;
                    case 'textarea':
                        $tmp = $this->field($this->model, $key, $optEl)->textarea(['class'=>'ckeditor'], $opt);
                        if(!empty($element['label'])) $tmp->label($element['label']);
                        $config['options'][] = $tmp;
                    break;
                    case 'captcha':
                        $config['options'][] = $this->field($this->model, $key, $optEl)
                            ->widget('\yii\captcha\Captcha',
                                [
                                    'captchaAction' => 'captcha',
                                ]
                            );
                        break;
                    case 'date':
                        $config['options'][] = '<div style="padding-left: 0;" class="col-md-6" data-date-format="dd.mm.yyyy hh:ii:ss" data-date-start-date="Date.default">'.
                            $this->field($this->model, $key, $optEl)->input('text', ['class'=>'datepicker-autoClose form-control']).
                            '</div>';
                        break;
                    case 'datetime':
                        $config['options'][] = '<div style="padding-left: 0;" class="col-md-6" data-date-format="dd.mm.yyyy hh:ii" data-date-start-date="Date.default">'.
                            $this->field($this->model, $key, $optEl)->input('text', ['class'=>'datetimepicker-autoClose form-control']).
                            '</div>';
                        break;
                    case 'file':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->fileInput($opt);
                        break;
                }
            }
            if($element != '</div>'){
                /*временная заплатка*/
                $config['options'][] = '<div style="clear: both;"></div>';
                /* **************** */ 
            }
		}
		unset($config['elements']);
        if($metaFlags) {
            //die(print_r($model->metaTag));
            $config['options'][] = '<h4>Meta-теги страницы:</h4>';
            $config['options'][] = $this->field($meta, 'language')->hiddenInput()->label(false);
            $config['options'][] = $this->field($meta, 'title');
            $config['options'][] = $this->field($meta, 'description');
            $config['options'][] = $this->field($meta, 'keywords');
        }
        if ($config['buttons']){
            foreach($config['buttons'] as $buttonName => $button) 
            {
                if($button['type'] == 'htmlBlock')
                    $config['options'][] = $button['value'];
                else if($button['type'] == 'submit')
                    $config['options'][] = (!empty($button['class'])) ? \yii\helpers\Html::submitButton($button['value'], ['class'=> $button['class']])
                        : \yii\helpers\Html::submitButton($button['value'], ['class'=> 'btn btn-success']);
                else if($button['type'] == 'cancel')
                    $config['options'][] = \yii\helpers\Html::resetButton($button['value'], ['class'=> 'btn btn-default']);
                else if($button['type'] == 'danger')
                    $config['options'][] = \yii\helpers\Html::button($button['value'], ['class'=> 'btn btn-danger', 'id' => $buttonName]);
                else
                    $config['options'][] = \yii\helpers\Html::button($button['value'], ['class'=> 'btn btn-info', 'id' => $buttonName]);

                $config['options'][] = ' ';
            }
        }
        if (array_key_exists('activeForm', $config)) {
            foreach($config['activeForm'] as $key=>$option) {
                $this->options[$key] = $option;
            }
        }
		
		unset($config['buttons']);

        self::initCustom($config);

        return true;
    }

    public function initCustom($config) {
        ob_start();
        ob_implicit_flush(false);
        $form = ActiveForm::begin(ArrayHelper::merge(
            [
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnType' => true,
            ],
            $config['activeForm']
        ));
        foreach($config['options'] as $item) {
            echo $item;
        }
        $form->end();
        $out = ob_get_contents();
        ob_get_clean();
        $this->out = $out;
        self::run();
    }


    public static function getFullAlias($alias)
    {
        return $alias;
		list($module, $form) = explode(".", $alias, 2);
        return "application.modules.{$module}.forms.{$form}";
    }

    // Убран static
    public function getFormConfig($alias, $model)
    {
        if (is_string($alias))
        {
            $alias = self::getFullAlias($alias);
            return require(\Yii::$app->basePath . '/..' .\yii::getAlias($alias) . '.php');
        }
        else
        {
            return $alias;
        }
    }

    public function renderBody()
    {
        $output = parent::renderBody();

        if (!($this->getParent() instanceof self))
        {
            if ($this->side == 'admin')
            {
                $this->attributes['class'] = 'admin_form';
                return $this->getParent()->msg('Поля отмеченные * обязательны.', 'info') . $output;
            }
        }

        return $output;
    }


    public function renderElement($element)
    {
        if (is_string($element))
        {
            if (($e = $this[$element]) === null && ($e = $this->getButtons()->itemAt($element)) === null
            )
            {
                return $element;
            }
            else
            {
                $element = $e;
            }
        }
        //        if ($element->getVisible())
        //        {
        if ($element instanceof CFormInputElement)
        {
            if ($element->type === 'hidden')
            {
                return "<div style=\"visibility:hidden\">\n" . $element->render() . "</div>\n";
            }
            else
            {
                return $this->_renderElement($element);
            }
        }
        else if ($element instanceof CFormButtonElement)
        {
            return $element->render() . "\n";
        }
        else
        {
            return $element->render();
        }
        //        }
        //        return '';
    }


    private function _renderElement($element)
    {
        if ($element instanceof self)
        {
            $this->_addAdminClasses($element);
            return $element->render();
        }

        if ($this->side == 'admin')
        {
            $this->_addAdminClasses($element);
            $tpl = '_adminForm';
        }
        elseif ($this->side = 'client')
        {
            $this->_addClientClasses($element);
            $tpl = '_form';
        }
        else
        {
            $tpl = '_form';
        }

        //        $element->attributesadminForm['data-hint']  = $element->hint;

        $class = $element->type;
        if (isset($element->attributes['parentClass']))
        {
            $class .= ' ' . $element->attributes['parentClass'];
        }

        $res = CHtml::openTag('dl', array('class'=> $class));
        $res .= CHtml::openTag('dd');

        $res .= Yii::app()->controller->renderPartial('application.views.layouts.' . $tpl, array(
            'element' => $element,
            'form'    => $element->parent
        ), true);
        $res .= CHtml::closeTag('dd');
        $res .= CHtml::closeTag('dl');

        return $res;
    }


    private function _addAdminClasses(&$element)
    {
        $data = $element->type;
        $attr = 'class';
        switch ($element->type)
        {
            case 'date':
                $data = array('class'=>$data.' text date_picker');
                $attr = 'htmlOptions';
                break;
            case 'datetime':
                $data = array('class'=>$data.' text date_picker');
                $attr = 'htmlOptions';
                break;
            case 'password':
                $data = $data.' text';
                break;
            case 'dropdownlist':
                $data = $data.' cmf-skinned-select';
                break;
            default:
                ;
        }
        $element->attributes[$attr] = $data;
    }


    private function _addClientClasses(&$element)
    {

    }


    public function clear()
    {
        $this->_clear = true;
    }


    public function renderButtons()
    {
        if (!($this->getParent() instanceof self) && !$this->buttons->itemAt('back') &&
            $this->cancel_button_show && $this->side == 'admin'
        )
        {
            $this->buttons->add("back", array(
                'type'  => 'button',
                'value' => 'Отмена',
                'url'   => Yii::app()->controller->createUrl('manage'),
                'class' => 'back_button submit small'
            ));
        }

        return parent::renderButtons();
    }


    /***** Функции оформления формы *******/

    function addAttributesToButtons()
    {
        foreach ($this->buttons as $i => $button)
        {
            if ($this->side == 'admin')
            {
                $length = mb_strlen($button->value, 'utf-8');

                $class = isset($button->attributes['class']) ?
                    $button->attributes['class'] . " submit" : "submit";

                if ($length > 11)
                {
                    $class .= ' long';
                }
                elseif ($length > 6)
                {
                    $class .= ' mid';
                }
                else
                {
                    $class .= ' small';
                }

                $button->attributes['class'] = $class;
            }
            else
            {

            }
            $this->buttons[$i] = $button;
        }
    }


    function formatDateAttributes()
    {
        if (!$this->model)
        {
            return false;
        }

        $model = $this->model;
        foreach ($model->attributes as $attr => $value)
        {
            if (Yii::app()->dater->isDbDate($value))
            {
                $model->$attr = Yii::app()->dater->formFormat($value);
            }
        }

        $this->model = $model;
    }


    //недоделанные функции
    function renderHint($element)
    {
        if (isset($element->hint))
        {
            $hint = trim($element->hint);
            if (!empty($hint))
            {
                echo "<span class='form_element_hint'>{$hint}</span>";
            }
        }

    }

//нужно еще добавить вывод городов

}
