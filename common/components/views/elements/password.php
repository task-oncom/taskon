<?php echo $field->begin();?>
<div class="form-group">
    <?php echo Html::activeLabel( $model, $elem, $options )?>
    <div class="col-md-9">
        <?php echo Html::activePasswordInput($model, $elem);?>
    </div>
</div>
<?php echo $field->end();?>