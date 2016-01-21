<?php
class TestingBreadCrumb extends CWidget 
{
    public $crumbs = array();
    public $delimiter = ' <span>\</span> ';
 
    public function run() 
    {
        $this->render('TestingBreadCrumb');
    }
}
?>