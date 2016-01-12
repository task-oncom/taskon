<?php
namespace common\components\zii;
class LinkPager extends \yii\widgets\LinkPager
{

    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if ($hidden || $selected)
            $class.=' ' . ($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
        if ($selected)
            return '<li class="' . $class . '"><span>' . $label . '</span></li>';
        else
            return '<li class="' . $class . '"><span>' . CHtml::link($label, $this->createPageUrl($page)) . '</span></li>';
    }


}