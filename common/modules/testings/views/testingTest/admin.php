<?php
$this->breadcrumbs=array(
	'Testing Tests'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List TestingTest', 'url'=>array('index')),
	array('label'=>'Create TestingTest', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('testing-test-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Testing Tests</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'testing-test-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'session_id',
		'name',
		'minutes',
		'questions',
		'pass_percent',
		/*
		'mg_percent',
		'te_percent',
		'mg_count',
		'te_count',
		'create_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
