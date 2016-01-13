<?php
namespace common\components;
use \yii\db\ActiveRecord;
class BaseForm extends \yii\widgets\ActiveForm
{
    private $_clear = false;

    public $side;
	
	public $model;
	
	public $out;

    public $cancel_button_show = true;
	
	public function run() {
		parent::run();
		$view = $this->getView();
        //ActiveFormAsset::register($view);
		/*$view->registerCssFile('/plugins/bootstrap-datepicker/css/datepicker.css', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerCssFile('/plugins/bootstrap-datepicker/css/datepicker3.css', ['position' => \yii\web\View::POS_HEAD ]);*/
		$view->registerCssFile('/plugins/bootstrap-datetimepicker/css/datetimepicker.css', ['position' => \yii\web\View::POS_HEAD ]);
		
		$view->registerCssFile('/plugins/switchery/switchery.min.css', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerCssFile('/plugins/powerange/powerange.min.css', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerJsFile('/js/form-plugins.demo.min.js', ['position' => \yii\web\View::POS_END ]);
		
		$view->registerJsFile('/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js', ['position' => \yii\web\View::POS_END ]);
		$view->registerJsFile('/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.ru.js', ['position' => \yii\web\View::POS_END ]);
		$view->registerJsFile('/plugins/switchery/switchery.min.js', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerJsFile('/plugins/powerange/powerange.min.js', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerJsFile('/js/form-slider-switcher.demo.min.js', ['position' => \yii\web\View::POS_HEAD ]);
		
		/*$view->registerJsFile('/plugins/ckeditor/ckeditor.js', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerJsFile('/plugins/ckeditor/config.js', ['position' => \yii\web\View::POS_HEAD ]);*/
		
		//$view->registerJsFile('//tinymce.cachefly.net/4.1/tinymce.min.js', ['position' => \yii\web\View::POS_HEAD ]);
		$view->registerJsFile('/plugins/tinymce/js/tinymce/tinymce.min.js', ['position' => \yii\web\View::POS_END ]);
		
		$view->registerJsFile('/js/apps.min.js', ['position' => \yii\web\View::POS_HEAD ]);
		
$js = <<<JS
	$('.datepicker-autoClose').datetimepicker({
        todayHighlight: true,
		language: "ru",
        autoclose: true,
		format: "dd.mm.yyyy hh:ii:ss",
		
    });
	FormSliderSwitcher.init();
tinymce.init({
    selector: "textarea",theme: "modern",
    language: "ru_RU",
    plugins: [
         "advlist autolink link image code lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
   image_advtab: true ,
   forced_root_block : false,
   external_filemanager_path:"/filemanager/",
   filemanager_title:"Responsive Filemanager" ,
   external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
 });
JS;
		
		$view->registerJs($js, \yii\web\View::POS_END, 'formLoad');
		$view->registerCss(".control-label {min-width: 100px;}");
	}
	
	public function __construct($config, $model = null, $parent = null)
    {
        $this->model = $model;
        $behaviors = $model->behaviors();
        $meta = false;
        if(!empty($behaviors['meta'])) $meta = true;
        if(empty($model->metaTags)) {
            $meta = new \common\models\MetaTags();
            $meta->language = 'ru';
        }
        else $meta = $model->metaTags;
		ob_start();
        ob_implicit_flush(false);
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
            $config = self::getFormConfig($config);
        }
		//$config['ActiveForm'] = $config['activeForm'];
		//die(print_r($config['elements']));
		unset($config['activeForm']);
		$config['options'] = [];
		//die(print_r(\yii::$app->controller->getAction('captcha')));
        $config['options'][] = $this->errorSummary($model);
		foreach($config['elements'] as $key=>$element) {
			if(!is_array($element))
				$config['options'][] = $element;
			else {
                $opt = [];
                $optEl = [];
                if(!empty($element['empty']))
                    $opt['prompt'] = $element['empty'];
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
                if(!empty($element['empty']))
                    $opt['value'] = $element['empty'];
                switch($element['type']) {
                    case 'checkbox':
                        $opt['data-render'] = 'switchery';
                        $opt['data-theme'] = 'default';
                        $opt['data-classname'] = 'switchery';
                        $config['options'][] = $this->field($this->model, $key)->checkbox(
                            $opt,
                            false
                        );
                    break;
                    case 'radio':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->radio($opt);
                    break;
                    case 'text':
                    case 'email':
                    case 'password':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->input($element['type'], $opt);
                    break;
                    case 'hidden':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->hiddenInput($opt)->label(false);
                    break;
                    case 'dropdownlist':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->dropDownList($element['items'],$opt);
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
                        $config['options'][] = $this->field($this->model, $key, $optEl)->textarea(['class'=>'ckeditor'], $opt);
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
                    case 'file':
                        $config['options'][] = $this->field($this->model, $key, $optEl)->fileInput();
                        break;
                }
            }
			/*временная заплатка*/
			$config['options'][] = '<div style="clear: both;"></div>';
			/* **************** */
		}
		unset($config['elements']);
        if($meta) {
            //die(print_r($model->metaTag));
            $config['options'][] = '<b>META:</b><br>';
            $config['options'][] = $this->field($meta, 'language')->hiddenInput()->label(false);
            $config['options'][] = $this->field($meta, 'title');
            $config['options'][] = $this->field($meta, 'keywords');
            $config['options'][] = $this->field($meta, 'description');
        }
		foreach($config['buttons'] as $button) {
			if($button['type'] == 'submit')
				$config['options'][] = \yii\helpers\Html::submitButton($button['value'], ['class'=> 'btn btn-primary']);
			else if($button['type'] == 'cancel')
				$config['options'][] = \yii\helpers\Html::resetButton($button['value'], ['class'=> 'btn btn-danger']);
			else
				$config['options'][] = \yii\helpers\Html::button($button['value'], ['class'=> 'btn btn-info']);
		}
		unset($config['buttons']);
//die(print_r($config));
        ob_start();
        ob_implicit_flush(false);
        parent::__construct($config, $model, $parent);
        //$this->addAttributesToButtons();
        //$this->formatDateAttributes();
		self::run();
		$out = ob_get_contents();
		
		ob_get_clean();
        echo $out;
        die();
		$repl = [];
		$repl[] = 'method="post';
		$repl[] = '&lt;/div&gt;">';
		for ( $i=0; $i <= 30; $i++) {$repl[] = '" '.$i.'="';}
		$out = str_replace($repl,['method="post">','&lt;/div&gt;',''],$out);
		$out = html_entity_decode($out);

		$ret = $out;			

		$this->out = $ret;
		return $ret;
    }


    public static function getFullAlias($alias)
    {
        return $alias;
		list($module, $form) = explode(".", $alias, 2);
        return "application.modules.{$module}.forms.{$form}";
    }
	
	/*public function render*/


    public static function getFormConfig($alias)
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


    /*public function __toString()
    {
        try
        {
            $cs = Yii::app()->clientScript;

            if (!($this->parent instanceof self))
            {
                //$id = $this->activeForm['id'];
                if ($this->side == 'client')
                {
//                    $cs
//                        ->registerScriptFile('/js/plugins/clientForm/inFieldLabel/jquery.infieldlabel.js')
//                        ->registerScriptFile('/js/plugins/clientForm/clientForm.js')
//                        ->registerCssFile('/js/plugins/clientForm/form.css')->registerScript(
//                        $id . '_baseForm', "$('#{$id}').clientForm()");
                }
                else
                {
                    $cs->registerScriptFile('/js/admin/admin_form.js')
                        ->registerScriptFile('/js/admin/admin_form.js')
                        ->registerScriptFile('/js/plugins/adminForm/buttonSet.js')
                        ->registerScriptFile('/js/plugins/adminForm/tooltips/jquery.atooltip.js')
                        ->registerCssFile('/js/plugins/adminForm/tooltips/atooltip.css')
                        ->registerScriptFile('/js/plugins/adminForm/chosen/chosen.jquery.js')
                        ->registerCssFile('/js/plugins/adminForm/chosen/chosen.css');
                    ;
                }
            }

            if ($this->_clear)
            {
                $cs->registerScript('clearForm', '$(function()
                {
                    $(":input","#' . $this->activeForm['id'] . '")
                        .not(":button, :submit, :reset, :hidden")
                        .val("")
                        .removeAttr("checked")
                        .removeAttr("selected");
                })');
            }


            return parent::__toString();
        } catch (Exception $e)
        {
            Yii::app()->handleException($e);
        }
    }*/


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
