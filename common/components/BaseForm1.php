<?php
namespace common\components;
use yii\widgets\ActiveForm;

class BaseForm extends ActiveForm
{
    private $_clear = false;

    public $side;

    public $cancel_button_show = true;

    public function __construct($config, $model = null, $parent = null)
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
    }


    public static function getFullAlias($alias)
    {
        list($module, $form) = explode(".", $alias, 2);
        return "application.modules.{$module}.forms.{$form}";
    }


    public static function getFormConfig($alias)
    {
        if (is_string($alias))
        {
            $alias = self::getFullAlias($alias);
            return require(Yii::getPathOfAlias($alias) . '.php');
        }
        else
        {
            return $alias;
        }
    }


    public function __toString()
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
