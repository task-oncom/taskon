<?php
Yii::import('zii.widgets.grid.CGridColumn');
class AccessColumn extends CGridColumn
{
    public $name;
    public $htmlOptions = array('class'=> 'checkbox-column');
    public $headerHtmlOptions = array('class'=> 'checkbox-column');
    public $footerHtmlOptions = array('class'=> 'checkbox-column');
    public $checkBoxHtmlOptions = array();

    public $role;
    public $action;


    public function init()
    {
        if (isset($this->checkBoxHtmlOptions['name']))
        {
            $name = $this->checkBoxHtmlOptions['name'];
        }
        else
        {
            $name = $this->id;
            if (substr($name, -2) !== '[]')
            {
                $name .= '[]';
            }
            $this->checkBoxHtmlOptions['name'] = $name;
        }
        $this->grid->onRegisterScript = array($this, 'registerScript');
        parent::init();
    }


    public function registerScript()
    {
        $cs  = Yii::app()->clientScript;
        $url = Yii::app()->controller->createUrl('/rbac/roleAdmin/setAccess', array('role' => $this->role));
        $cs->registerScript('accessColumn', "
            $(document).ready(function()
            {
                $('.view-op > input, .edit-op > input').change(function()
                {
                    $.get('{$url}' , {
                        allow:this.checked ? 1 : 0,
                        item:$(this).val(),
                        is_object:$(this).data('is-object')
                    });
                    return false;
                });
            });");
    }


    public function renderDataCell($row)
    {
        $data = $this->grid->dataProvider->data[$row];

        $is_module =
            isset($data["module"]) && method_exists(Yii::app()->getModule($data['module']), 'getOperations');
        $is_op     = isset($data["op_id"]) && $data["op_id"];

        parent::renderDataCell($row);

        if (isset($this->htmlOptions['colspan']))
        {
            unset($this->htmlOptions['colspan']);
        }
        if (isset($this->htmlOptions['width']))
        {
            unset($this->htmlOptions['width']);
        }
    }


    public function renderDataCellContent($row, $data)
    {
        $options = $this->checkBoxHtmlOptions;
        $name    = $options['name'];
        unset($options['name']);
        $options['id'] = $this->id . '_' . $row;

        $am = Yii::app()->authManager;
        if (isset($data["op_id"]) && $data["op_id"])
        {
            $options['value']          = $this->action . '_' . $data["op_id"];
            $options['data-is-object'] = false;

            $role = Yii::app()->authManager->getAuthItem($this->role);
            $role->checkAccess($options["value"]);
            $checked                   = RbacModule::isItemAllow($this->role, $options["value"]);
        }
        else if (
            isset($data["module"]) && method_exists(Yii::app()->getModule($data['module']), 'getOperations')
        )
        {
            $url = Yii::app()->createUrl("/rbac/roleAdmin/moduleOperations", array(
                "role"   => $this->role,
                "module" => $data["module"]
            ));
            echo CHtml::link('Права доступа модуля', $url);
            return;
        }
        else if (is_object($data))
        {
            $options['value']          = get_class($data) . '_' . $data->id . '_' . $this->action;
            $options['data-is-object'] = true;
            $checked                   = RbacModule::isObjectItemAllow($this->role, get_class($data), $data->id, $this->action);
        }
        else
        {
            echo "Раздел не поддерживает разграничение прав";
            return;
        }

        echo CHtml::checkBox($name, $checked, $options);
    }
}