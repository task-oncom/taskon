<?php
namespace common\components\zii;
use yii\helpers\Html;
class LinkPager extends \yii\widgets\LinkPager
{
	
	public $linkOptions = ['class' => 'paginate_button'];
	public $firstPageLabel = false;
	public $lastPageLabel = false;
	public $prevPageLabel = 'Назад';
	public $nextPageLabel = 'Вперед';
	public $activePageCssClass = 'current';

    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if ($hidden || $selected)
            $class.=' ' . ($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
        if ($selected)
            return '<li class="' . $class . '"><span>' . $label . '</span></li>';
        else
            return '<li class="' . $class . '"><span>' . CHtml::link($label, $this->createPageUrl($page)) . '</span></li>';
    }
	
	/**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();
		//if($currentPage < 1) $currentPage = 1;
        // first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
		//die(print_r($i . '--' . $currentPage));
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }
		$this->options['class'] = 'dataTables_paginate paging_simple_numbers';
		$this->options['id'] = 'data-table_paginate';
        return Html::tag('div', implode("\n", $buttons), $this->options);
    }

    /**
     * Renders a page button.
     * You may override this method to customize the generation of page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $disabled whether this page button is disabled
     * @param boolean $active whether this page button is active
     * @return string the rendering result
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        $linkOptions = $this->linkOptions;
		if ($active) {
            Html::addCssClass($linkOptions, $this->activePageCssClass);
			Html::addCssClass($linkOptions, $this->disabledPageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($linkOptions, $this->disabledPageCssClass);

			return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
        }
        
        $linkOptions['data-page'] = $page;

        return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
    }

}