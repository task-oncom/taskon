<?php

Yii::import('zii.widgets.grid.CGridColumn');

class MarkBoxColumn extends CGridColumn
{
	public $checked;
	/**
	 * @var array the HTML options for the data cell tags.
	 */
	public $htmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the header cell tag.
	 */
	public $headerHtmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the footer cell tag.
	 */
	public $footerHtmlOptions=array('class'=>'checkbox-column');
	/**
	 * @var array the HTML options for the checkboxes.
	 */
	public $checkBoxHtmlOptions=array();

	/**
	 * @var string the template to be used to control the layout of the header cell.
	 * The token "{item}" is recognized and it will be replaced with a "check all" checkbox.
	 * By default if in multiple checking mode, the header cell will display an additional checkbox, 
	 * clicking on which will check or uncheck all of the checkboxes in the data cells.
	 * See {@link selectableRows} for more details.
	 * @since 1.1.11
	 */
	public $headerTemplate='{item}';

	public $updateUrl;
	/**
	 * Initializes the column.
	 * This method registers necessary client script for the checkbox column.
	 */
	public function init()
	{
		if(isset($this->checkBoxHtmlOptions['name']))
			$name=$this->checkBoxHtmlOptions['name'];
		else
		{
			$name=$this->id;
			if(substr($name,-2)!=='[]')
				$name.='[]';
			$this->checkBoxHtmlOptions['name']=$name;
		}
		$name=strtr($name,array('['=>"\\[",']'=>"\\]"));


		$js=<<<CBALL
$(document).delegate('#{$this->id}_all','click',function() {
	//групповой выбор/сброс
	var th = this, checked=this.checked, data = {};
	
	th.disabled = true;
	$("input[name='$name']:not(:disabled)").each(function() {		
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
      		$("input[name='$name']").each(function() {
      			this.disabled = false;
      		});
        }
      });
    });


$(document).delegate("input[name='$name']", 'click',function() {
	var checked=this.checked, data = {}, th = this;
		
	$('#{$this->id}_all').prop('checked', $("input[name='$name']").length==$("input[name='$name']:checked").length);
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
  $("#{$this->id}_all, input[name='$name']").prop('checked', false);
  $("#sendMarkup").hide();
  $("#resetMarkup").hide();  

  $.ajax({
    type: 'POST',
    url: $(this).attr('href')
  });
  return false;
});
CBALL;

		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
	}

	/**
	 * Renders the header cell content.
	 * This method will render a checkbox in the header when {@link selectableRows} is greater than 1
	 * or in case {@link selectableRows} is null when {@link CGridView::selectableRows} is greater than 1.
	 */
	protected function renderHeaderCellContent()
	{
		if(trim($this->headerTemplate)==='')
		{
			//echo $this->grid->blankDisplay;
			return;
		}

		$item = CHtml::checkBox($this->id.'_all',false);

		echo strtr($this->headerTemplate,array(
			'{item}'=>$item,
		));
	}

	/**
	 * Renders the data cell content.
	 * This method renders a checkbox in the data cell.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		$value=$this->grid->dataProvider->keys[$row];
        /*
		$checked = false;
		if($this->checked!==null)
			$checked=$this->evaluateExpression($this->checked,array('data'=>$data,'row'=>$row));
        */
        
        $marked = $this->grid->controller->getMarked(Yii::app()->request->getQuery('session'));
        $checked = in_array($data->id, $marked);
		$options=$this->checkBoxHtmlOptions;
		$name=$options['name'];
		unset($options['name']);
		$options['value']=$value;
		$options['id']=$this->id.'_'.$row;
		echo CHtml::checkBox($name,$checked,$options);
	}
}