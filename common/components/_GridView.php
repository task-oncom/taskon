<?php
use himiklab\sortablegrid\SortableGridView;
namespace common\components;

class GridView extends \himiklab\sortablegrid\SortableGridView
{

    public $cssFile = null;
    public $filters;
    public $order_links = false;
    public $pager = array('class' => 'LinkPager');
    public $buttons = null;
    public $sortable = false;
    public $many_many_sortable = false;
    public $cat_id = false;
    public $_pocket;
    public $mass_removal = false;
    public $formatDateValues = true;
    public $jsPlugin = 'grid';
    public $template = '{pagerSelect}{summary}<br/>{pager}<br/>{clipboard}\n{items}<br/>{pager}';

    public function init()
    {
        $this->attachBehaviors($this->behaviors());
        parent::init();
        if($this->formatDateValues)
        $this->formatDateValues();
    }


    public function behaviors()
    {
        return array();
    }


    public function formatDateValues()
    {
        $data = $this->dataProvider->getModels();
        
		foreach ($data as $item)
        {
            foreach ($item as $attr => $value)
            {
                if (!\Yii::$app->dater->isDbDate($value))
                {
                    continue;
                }

                $no_values = array('0000-00-00 00:00:00', '0000-00-00');
                $new_value = in_array($value, $no_values) ? null : Yii::app()->dater->readableFormat($value);

                if (is_array($item))
                {
                    $item[$attr] = $new_value;
                }
                else
                {
                    $item->$attr = $new_value;
                }
            }
        }

        $this->dataProvider->setModels($data);
    }


    /**
     * Добавляет колонки перед последней колонкой
     *
     * @param $configs конфиги для колонок
     */
    public function addColumns($configs, $pos = 0)
    {
        $last_index = $pos >= 0 ? $pos : count($this->columns) + $pos;
        $configs[] = $this->columns[$last_index];
        array_splice($this->columns, $last_index, 1, $configs);
    }


    /**
     * Добавляет колонку перед последней колонкой
     * @param $config конфиг колонки
     */
    public function addColumn($config, $pos = 0)
    {
        $last_index = $pos >= 0 ? $pos : count($this->columns) + $pos;
        $configs = array(
            $config, $this->columns[$last_index]
        );
        array_splice($this->columns, $last_index, 1, $configs);
    }


    public function initColumns()
    {

        if ($this->mass_removal)
        {
            $this->addColumn(array(
                'class' => 'CCheckBoxColumn',
                'header' => "<input type='checkbox' class='object_checkboxes'>",
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array(
                    'class' => 'object_checkbox'
                )
            ));
        }
        parent::initColumns();
    }


    public static function orderLinks($data)
    {
        $class = get_class($data);

        return "<a href='/main/mainAdmin/changeOrder/id/{$data->id}/order/up/class/{$class}/from/" .
                base64_encode($_SERVER["REQUEST_URI"]) . "' />
                    <img src='/img/admin/icons/arrow_up.png' border='0' />
                </a>
                &nbsp;
                <a href='/main/mainAdmin/changeOrder/id/{$data->id}/order/down/class/{$class}/from/" .
                base64_encode($_SERVER["REQUEST_URI"]) . "' />
                    <img src='/img/admin/icons/arrow_down.png' border='0'  />
                </a>";
    }


    public function renderItems()
    {
		$return = '';
        if ($this->dataProvider->getCount() > 0 || $this->showTableOnEmpty)
        {
            $return .= "<table class='".$this->tableOptions['itemsCssClass']."' cellpadding='0' cellspacing='0' width='100%'>\n";
            $return .= $this->renderTableHeader();
            $return .= $this->renderTableBody();
            $return .= $this->renderTableFooter();
            $return .= "</table>";

            if ($this->mass_removal)
            {
                $return .= "<input type='submit' class='submit tiny red' value='удалить' id='mass_remove_button'>";
            }
        }
        else
        {
            $return .= $this->renderEmptyText();
        }
		return $return;
    }


    public function renderPocket()
    {
        if ($this->many_many_sortable)
        {
            $save = $this->dataProvider->getData(true);
            $this->dataProvider->setData($this->_pocket);
            $data = $this->_pocket;
            $n = count($data);
            echo "<tbody class=\"pct sortt\">\n";

            echo '<tr id="pct_empty"><td colspan="' . count($this->columns) . '">';
            echo CHtml::tag('span', array('class' => 'empty'), "Буфер обмена<br/>
                                Перетащите товара в данную область, что бы перенести ее на другую страницу данного раздела Панели управления.");
            echo "</td></tr>\n";

            if ($n > 0)
            {
                foreach ($data as $row => $info)
                {
                    $this->renderTableRow($row, $info);
                }
            }
            echo "</tbody>\n";
            $this->dataProvider->setData($save);
        }
    }

	public function renderTableBody()
    {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows)) {
            $colspan = count($this->columns);

            return "<tbody class=\"sc sortt\">\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        } else {
            return "<tbody class=\"sc sortt\">\n" . implode("\n", $rows) . "\n</tbody>";
        }
    }


    public function renderPagerSelect()
    {
        echo '<div class="pager-select">';
        $value = null;
        if (isset(Yii::app()->session[get_class($this->filter) . "PerPage"]))
        {
            $value = Yii::app()->session[get_class($this->filter) . "PerPage"];
        }

        $select = CHtml::dropDownList("pager_pages", $value, array_combine(range(10, 500, 5), range(10, 500, 5)), array(
                    'class' => 'pager_select',
                    'model' => get_class($this->filter)
                ));

        echo "&nbsp; &nbsp;Показывать на странице: {$select}";
        echo '</div>';
    }


    /**
     * Изначально регистрируются 2 плагина gridBase и grid
     * Если установить значение свойства jsPlugin, то подключится так же плагин /css/admin/gridview/{$this->jsPlugin}.js
     * И на сам grid будет инициализироват плагин с названием из jsPlugin
     */
    public function registerClientScript()
    {
        parent::registerClientScript();
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery.ui');
        $cs->registerScriptFile("/js/plugins/gridview/gridBase.js");
        $cs->registerScriptFile("/js/plugins/gridview/grid.js");

        if ($this->jsPlugin != 'grid')
        {
            $cs->registerScriptFile("/js/plugins/gridview/{$this->jsPlugin}.js");
        }
        $options = CJavaScript::encode(array(
                    'mass_removal' => $this->mass_removal
                ));
        $cs->registerScript($this->getId() . 'CmsUI', "
            $('#{$this->getId()}').{$this->jsPlugin}({$options});
        ");

        $this->onRegisterScript(new CEvent);
    }


    public function onRegisterScript($event)
    {
        $this->raiseEvent('onRegisterScript', $event);
    }


}
