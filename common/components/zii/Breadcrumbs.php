<?php
Yii::import('zii.widgets.CBreadcrumbs');
class Breadcrumbs extends CBreadcrumbs
{
    public $homeLink = false;
    public $htmlOptions = array('id'=> 'breadcrumb_links');
    public $separator = ' <span>/</span> ';
    public $currentPageClass = 'curr_page';

    public $showForm = false;
    public $form;
    public $formTitle;
    public $formClass;
    
    public function run()
    {
        echo '<div id="breadcrumb">';
        if ($this->showForm)
        {
            $this->widget('faq.portlets.ModalForm', array('form'=>$this->form, 'formClass'=>$this->formClass, 'formTitle'=>$this->formTitle));
        }
        $this->_run();
        echo '</div>';
    }

    private function _run()
    {
        if (empty($this->links)) {
            return;
        }

        echo CHtml::openTag($this->tagName, $this->htmlOptions)."\n";
        $links = array();
        if ($this->homeLink === null) {
            $links[] = CHtml::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl);
        }
        else if ($this->homeLink !== false) {
            $links[] = $this->homeLink;
        }
        if (count($this->links) == 1)
        {
            $this->currentPageClass = 'alone-crumb';
        }
        foreach ($this->links as $label=> $url)
        {
            if (is_string($label) || is_array($url))
            {
                $links[] = CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
            }
            else
            {
                $links[] = CHtml::tag('span', array('class'=> $this->currentPageClass), $this->encodeLabel ? CHtml::encode($url) : $url);
            }
        }
        echo implode($this->separator, $links);
        echo CHtml::closeTag($this->tagName);
    }
}

