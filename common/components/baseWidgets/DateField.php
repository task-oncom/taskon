<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 06.04.2015
 * Time: 0:26
 */

namespace common\components\baseWidgets;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class DateField extends InputWidget {

    public $minYear = 16;
    public $maxYear = 90;
    public $selectClass = '';
    public $template = '{label}{input}';
    public $disabled = false;

    private function getDaysArr() {
        $days = [];
        for($i=1; $i <=31; $i++) {
            $days[str_pad($i, 2, "0", STR_PAD_LEFT)] = str_pad($i, 2, "0", STR_PAD_LEFT);
        }
        return $days;
    }

    private function getMonthArr() {
        $months = [
            '01' => Yii::t('app', 'Январь'),
            '02' => Yii::t('app', 'Февраль'),
            '03' => Yii::t('app', 'Март'),
            '04' => Yii::t('app', 'Апрель'),
            '05' => Yii::t('app', 'Май'),
            '06' => Yii::t('app', 'Июнь'),
            '07' => Yii::t('app', 'Июль'),
            '08' => Yii::t('app', 'Август'),
            '09' => Yii::t('app', 'Сентябрь'),
            '10' => Yii::t('app', 'Октябрь'),
            '11' => Yii::t('app', 'Ноябрь'),
            '12' => Yii::t('app', 'Декабрь'),
        ];

        return $months;
    }

    private function getYearArr() {
        $startYear = 1 * date('Y') - $this->maxYear;
        $endYear = 1 * date('Y') - $this->minYear;
        $years = [];
        for($i=$endYear;$i>=$startYear;$i--) {
            $years[$i] = $i;
        }

        return $years;
    }

    public function init() {
        parent::init();
    }

    public function run() {
        $input = '';
        $inp = '';
        $attrName = $this->attribute;
        $attrValue = $this->model->$attrName;
        if(empty($attrValue)) $attrValue = '01.01.'.(1 * date('Y') - $this->minYear);
        $day = date('d', strtotime($attrValue));
        $month = date('m', strtotime($attrValue));
        $year = date('Y', strtotime($attrValue));

        $this->options['class'] = '';
        if(!empty($this->selectClass))
            $this->options['class'] .= ' '.$this->selectClass;

        if($this->disabled)
            $this->options['disabled'] = 'disabled';

        $input .= strtr($this->template,[
           '{label}' => Html::label('<b>День</b>', $attrName.'_day'),
            '{input}' => Html::dropDownList($attrName.'_day', (empty($this->model->$attrName)?'':$day), $this->getDaysArr(), array_merge($this->options, ['id' => $attrName.'_day', 'onchange'=>'change_'.$this->attribute.'()','prompt' => 'День','data-placeholder' => 'День'])),
        ]);

        $input .= strtr($this->template,[
            '{label}' => Html::label('<b>Месяц</b>', $attrName.'_month'),
            '{input}' => Html::dropDownList($attrName.'_month', (empty($this->model->$attrName)?'':$month), $this->getMonthArr(), array_merge($this->options, ['id' => $attrName.'_month', 'onchange'=>'change_'.$this->attribute.'()','prompt' => 'Месяц','data-placeholder' => 'Месяц'])),
        ]);

        $input .= strtr($this->template,[
            '{label}' => Html::label('<b>Год</b>', $attrName.'_year'),
            '{input}' => Html::dropDownList($attrName.'_year', (empty($this->model->$attrName)?'':$year), $this->getYearArr(), array_merge($this->options, ['id' => $attrName.'_year', 'onchange'=>'change_'.$this->attribute.'()','prompt' => 'Год','data-placeholder' => 'Год'])),
        ]);

        $input .= Html::activeHiddenInput($this->model, $this->attribute, [
            /*'labelOptions' => ['label' => false],
            'options' => [ 'errorOptions' => ['style' => 'margin-left: 13px;']]*/
        ]);

        //$input .= str_replace('{item}',$inp,$this->tmpl);

        echo $input;
        $this->registerClientScript();
    }

    public function registerClientScript() {
        $js = [];
        $view = $this->getView();
        $js[] = " function change_".$this->attribute."(event){
            //event.preventDefault();
            var _day = $('#".$this->attribute.'_day'."').val();
            var _month = $('#".$this->attribute.'_month'."').val();
            var _year = $('#".$this->attribute.'_year'."').val();
            //alert(_year+'-'+_month+'-'+_day);
            $('#scclient-".$this->attribute."').val(_year+'-'+_month+'-'+_day);
            $('#scclient-".$this->attribute."').change();
        }";
        $view->registerJs(implode("\n", $js), Yii\web\View::POS_HEAD);
    }
}