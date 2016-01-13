<?php

/**
 * Модель PostPager
 */
class PostPager extends MyLinkPager
{
    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if($hidden || $selected)
            $class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);

        $html  = '<li class="' . $class . '"><span>';
        $html .= CHtml::openTag('form', array('method' => 'post', 'action' => $this->createPageUrl($page)));
        foreach ($_POST as $name => $value) {
            $html .= CHtml::hiddenField($name, $value);
        }
        $html .= CHtml::link($label, '#', array('onclick' => '$(this).closest("form").submit(); return false;'));
        $html .= CHtml::closeTag('form');
        $html .= '</span></li>';

        return $html;
    }
}