<style type="text/css">
	.ui-icon {
		display: inline-block;
		vertical-align: middle;
	}
	ol,ul {
		list-style: inherit;
		padding: 0 30px;
    	margin: 15px 0;
	}
	span, p, ol, ul {
		font-size: inherit;
	}
	span[data-toggle="tooltip"]
	{
		border-bottom: 1px dotted #4fa600;
	    color: #4fa600;
	    cursor: pointer;
	}
</style>

<?php if($model->content) {?>
	<p><?=$model->content?></p>
<? } ?>

<br>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<?php if($model->stages) {
		foreach ($model->stages as $i => $stage) { ?>
			<div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="heading-<?=$i?>">
			      	<h4 class="panel-title" style="padding-bottom:0;">
			        	<a role="button" <?if($i!=0){?>class="collapsed"<?}?> data-toggle="collapse" data-parent="#accordion" href="#collapse-<?=$i?>" aria-expanded="<?if($i==0){?>true<?}else{?>false<?}?>" aria-controls="collapse-<?=$i?>"><?=$stage->title?></a>
			      	</h4>
			    </div>
			    <div id="collapse-<?=$i?>" class="panel-collapse collapse <?if($i==0){?>in<?}?>" role="tabpanel" aria-labelledby="heading-<?=$i?>">
			      	<div class="panel-body">
			      		<?=$stage->content?>
			      	</div>
			    </div>
			</div>
		<? }
	} ?>
</div>