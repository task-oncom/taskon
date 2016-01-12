<?php

namespace common\components;

/**
 * CBreadcrumbs class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CBreadcrumbs displays a list of links indicating the position of the current page in the whole website.
 *
 * For example, breadcrumbs like "Home > Sample Post > Edit" means the user is viewing an edit page
 * for the "Sample Post". He can click on "Sample Post" to view that page, or he can click on "Home"
 * to return to the homepage.
 *
 * To use CBreadcrumbs, one usually needs to configure its {@link links} property, which specifies
 * the links to be displayed. For example,
 *
 * <pre>
 * $this->widget('zii.widgets.CBreadcrumbs', array(
 *     'links'=>array(
 *         'Sample post'=>array('post/view', 'id'=>12),
 *         'Edit',
 *     ),
 * ));
 * </pre>
 *
 * Because breadcrumbs usually appears in nearly every page of a website, the widget is better to be placed
 * in a layout view. One can define a property "breadcrumbs" in the base controller class and assign it to the widget
 * in the layout, like the following:
 *
 * <pre>
 * $this->widget('zii.widgets.CBreadcrumbs', array(
 *     'links'=>$this->breadcrumbs,
 * ));
 * </pre>
 *
 * Then, in each view script, one only needs to assign the "breadcrumbs" property as needed.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package zii.widgets
 * @since 1.1
 */
class MyBreadcrumbs extends \yii\widgets\Breadcrumbs
{
	/**
	 * @var string the separator between links in the breadcrumbs. Defaults to ' &raquo; '.
	 */
	public $separator=' &raquo; ';
	
	public $itemTemplate = '<li>{link}</li>';
	public $activeItemTemplate = '<li class="active">{link}</li>'; // template for all links
	public $options = ['tag' => 'ol', 'class' => 'breadcrumb pull-right'];

	/**
	 * Renders the content of the portlet.
	 */
	public function run()
	{
		if(empty(\yii::$app->controller->breadcrumbs))
			return;

		if($this->homeLink===null)
			$this->homeLink = ['label'=>'Главная', 'url'=>'/'];

		$c = 0;
		foreach(\yii::$app->controller->breadcrumbs as $item)
		{
			$c++;
			if(is_array($item)) {
				$label = array_keys($item);
				$url = array_values($item);

				if(iconv_strlen($label[0]) > 40)
				{
					$label[0] = \yii\helpers\StringHelper::truncate($label[0], 40);
				}

				$this->links[] = ['label' => $label[0], 'url' => $url[0]];
			}
			else
			{
				if(iconv_strlen($item) > 40)
				{
					$item = \yii\helpers\StringHelper::truncate($item, 40);
				}
				
				$this->links[] = $item;
			}
		}

		parent::run();
	}
}