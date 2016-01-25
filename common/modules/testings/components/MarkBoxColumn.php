<?php

namespace common\modules\testings\components;

use Closure;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class MarkBoxColumn extends \yii\grid\CheckboxColumn
{
	public $checked;

	public $contentOptions = ['class'=>'checkbox-column'];

	public $headerOptions = ['class'=>'checkbox-column'];

	public $footerOptions = ['class'=>'checkbox-column'];

	public $updateUrl;

    public $name = 'selection';

    public $checkboxOptions = [];

    public $multiple = true;

    public function init()
    {
        parent::init();
        if (empty($this->name)) 
        {
            throw new InvalidConfigException('The "name" property must be set.');
        }

        if (substr_compare($this->name, '[]', -2, 2)) 
        {
            $this->name .= '[]';
            // $this->checkBoxOptions['name'] = $name;
        }

		// $name = strtr($name, ['['=>"\\[",']'=>"\\]"]);


$js = <<<EOD
	$(document).delegate('.select-on-check-all','click',function() {
		//групповой выбор/сброс
		var th = this, checked=this.checked, data = {};
		
		th.disabled = true;
		$("input[name='{$this->name}']:not(:disabled)").each(function() {		
			data[this.value] =checked ? 1:0;
			this.checked=checked;
			this.disabled = true;
		});
		$.ajax({
	        type: 'POST',
	        url: '{$this->updateUrl}',
	        data: {data: data},
	        dataType: 'json',
	        success: function(data){
	          var el = $("#sendMarkup").html(data.title);

	          if(data.qty) {
	            el.show();
	            $("#resetMarkup").show();
	          } else {
	            el.hide();
	            $("#resetMarkup").hide();
	          }
	        },
	        complete: function(){
	            th.disabled = false;
	      		$("input[name='{$this->name}']").each(function() {
	      			this.disabled = false;
	      		});
	        }
	      });
	    });


	$(document).delegate("input[name='$this->name']", 'click',function() {
		var checked=this.checked, data = {}, th = this;
			
		$('.select-on-check-all').prop('checked', $("input[name='{$this->name}']").length==$("input[name='{$this->name}']:checked").length);
		th.disabled = true;
		data[this.value] =checked ? 1:0;
	  
		$.ajax({
	    type: 'POST',
	    url: '{$this->updateUrl}',
	    data: {data: data},
	    dataType: 'json',
	    success: function(data){
	      var el = $("#sendMarkup").html(data.title);
	      if(data.qty) {
	        el.show();
	        $("#resetMarkup").show();
	      } else {
	        el.hide();
	        $("#resetMarkup").hide();
	      }
	    },
	    complete: function(){
	  		th.disabled = false;
	    }
	  });

	});

	$(document).delegate("#resetMarkup", 'click', function(){
	  $(".select-on-check-all, input[name='{$this->name}']").prop('checked', false);
	  $("#sendMarkup").hide();
	  $("#resetMarkup").hide();  

	  $.ajax({
	    type: 'POST',
	    data: {reset:true},
	    url: $(this).attr('href')
	  });
	  return false;
	});
EOD;

		\Yii::$app->controller->view->registerJS($js, \yii\web\View::POS_END, __CLASS__);
    }


  //   if(trim($this->headerTemplate)==='')
		// {
		// 	//echo $this->grid->blankDisplay;
		// 	return;
		// }

		// $item = CHtml::checkBox($this->id.'_all',false);

		// echo strtr($this->headerTemplate,array(
		// 	'{item}'=>$item,
		// ));

    protected function renderHeaderCellContent()
    {
        $name = $this->name;

        if (substr_compare($name, '[]', -2, 2) === 0) 
        {
            $name = substr($name, 0, -2);
        }

        if (substr_compare($name, ']', -1, 1) === 0) 
        {
            $name = substr($name, 0, -1) . '_all]';
        } 
        else 
        {
            $name .= '_all';
        }

        $id = $this->grid->options['id'];

        $options = json_encode([
            'name' => $this->name,
            'multiple' => $this->multiple,
            'checkAll' => $name,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $this->grid->getView()->registerJs("jQuery('#$id').yiiGridView('setSelectionColumn', $options);");

        if ($this->header !== null || !$this->multiple) 
        {
            return parent::renderHeaderCellContent();
        } 
        else 
        {
            return Html::checkBox($name, false, ['class' => 'select-on-check-all']);
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {   	
        if ($this->checkboxOptions instanceof Closure) 
        {
            $options = call_user_func($this->checkboxOptions, $model, $key, $index, $this);
        } 
        else 
        {
            $options = $this->checkboxOptions;
            if (!isset($options['value'])) 
            {
                $options['value'] = is_array($key) ? json_encode($key, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $key;
            }
        }

        if(\Yii::$app->request->get('session'))
    	{
    		$marked = \Yii::$app->controller->getMarked(\Yii::$app->request->get('session'));
        	$checked = in_array($model->id, $marked);
        	return Html::checkbox($this->name, $checked, $options);
    	}
    	else
    	{
    		return Html::checkbox($this->name, !empty($options['checked']), $options);
    	}
    }
}