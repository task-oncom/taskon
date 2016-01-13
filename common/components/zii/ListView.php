<?php
Yii::import("zii.widgets.CListView");
class ListView extends CListView
{
    public $cssFile = '/css/yii/listview.css';
    public $pager = array(
        'class'   => 'LinkPager'
    );
    
public function renderKeys()
{
    echo CHtml::openTag('div',array(
        'class'=>'keys HiddenField',
       // 'style'=>'display:none',
        'title'=>Yii::app()->getRequest()->getUrl(),
    ));
    foreach($this->dataProvider->getKeys() as $key)
        echo "<span>".CHtml::encode($key)."</span>";
    echo "</div>\n";
}

}