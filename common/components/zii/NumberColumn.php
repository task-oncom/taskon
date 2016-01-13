<?php
class NumberColumn extends CDataColumn
{
    public $attribute;

    public function init()
    {
        parent::init();

        $attr = $this->attribute = $this->attribute ? $this->attribute : $this->name;
        $start = '_'.$attr.'_start';
        $end = '_'.$attr.'_end';
        $widget   = 'application.components.baseWidgets.InputWidget';
        $settings = array();

        $html = CHtml::tag('span', array('style'=> 'float:left'), 'От:');
        $request = new CHttpRequest();
        $html .= CHtml::textField('_'.$this->attribute.'_start', $request->getParam($start), array('style'=>'width: 61px; float: right;'));
        $this->filter .= CHtml::tag('div', array('style'=>'float: left; width: 100px;'), $html);

        $html = CHtml::tag('span', array('style'=> 'float:left'), 'До:');
        $html .= CHtml::textField('_'.$this->attribute.'_end', $request->getParam($end), array('style'=>'width: 61px; float: right;'));

        $this->filter .= CHtml::tag('div', array('style'=>'float: left; width: 100px;'), $html);
    }
}