<?php
namespace common\components\zii;
use yii\helpers\Html;
class FrontLinkPager extends \yii\widgets\LinkPager
{
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        $linkOptions = $this->linkOptions;
        if ($active) {
            //Html::addCssClass($this->linkOptions, $this->activePageCssClass);
            $options['class'] = $this->activePageCssClass;
            return Html::tag('li', $label, $options);
        }

        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            $linkOptions['readonly'] = 'readonly';
            Html::tag('li', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
        }

        $linkOptions['data-page'] = $page;

        return Html::tag('li', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }

    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            if($page  != 0 || $currentPage > 0)
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            if($currentPage + 1 <= $pageCount - 1)
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }
}