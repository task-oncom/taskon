<?php echo $field->begin();?>
<div class="form-group">
    <div class="col-md-9">
        <div class="checkbox">
            <label>
                <?php echo activeCheckbox( $model, $elem, $options  )?>
                <?php echo $model->getAttributeLabel($elem)?>
            </label>
        </div>
    </div>
</div>
<?php echo $field->end();?>