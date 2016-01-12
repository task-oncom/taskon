<?php
class TopMenuWidget extends Portlet
{
    public function renderContent()
    {
        //$cats = CatalogCategories::model()->findAllByAttributes(array('parent_id' => 0));
	    $cats = Menu::model()->visible()->ordered()->findAll();
        $this->render('top_menu_widget', array('categories' => $cats));
    }


}