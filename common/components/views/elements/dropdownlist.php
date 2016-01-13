<?php echo $field->begin();?>
<div class="form-group">
    <?php echo Html::activeLabel( $model, $elem, $options )?>
    <div class="col-md-9">
        <?php echo activeDropDownList( $model, $attribute, $items, $options )?>
    </div>
</div>
<?php echo $field->end();?>