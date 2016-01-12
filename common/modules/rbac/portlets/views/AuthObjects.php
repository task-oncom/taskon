<script type="text/javascript">
    $(function()
    {
        $('.auth_objects_table .action_link').click(function()
        {
            var action = $(this).data('action');

            var $checkboxes = $(".auth_objects_table input[data-action=" + action + "]");


            if ($(this).data('checked'))
            {
                $checkboxes.removeAttr('checked');
                $(this).data('checked', false)
            }
            else
            {
                $checkboxes.attr('checked', 'checked');
                $(this).data('checked', true)
            }

            return false;
        });
    });
</script>


<style type="text/css">
    .auth_objects_table td{
        width:      1px;
        text-align: center !important;
    }
</style>


<table border="0" class="auth_objects_table">
    <tr>
        <td style="width: 252px"><b>Группа пользователей</b></td>
        <td><a href="" class="action_link" data-action="View">Просмотр</a></td>
        <td><a href="" class="action_link" data-action="Edit">Правка</a></td>
    </tr>
    <?php foreach ($roles as $role): ?>
    <tr>
        <td><?php echo $role->description; ?></td>
        <?php foreach ($actions as $name => $label): ?>
        <?php

        $checked = RbacModule::isObjectItemAllow($role->name, get_class($this->model), $this->model->id, $name);
        $checked = $checked ? 'checked' : '';
        ?>
        <td>
            <input type="checkbox" data-action="<?php echo $name; ?>" name="AuthObject[<?php echo $role->name; ?>][<?php echo $name; ?>]" value="<?php echo $this->model->primaryKey; ?>" <?php echo $checked; ?> />
        </td>
        <?php endforeach ?>
    </tr>
    <?php endforeach ?>
</table>    
