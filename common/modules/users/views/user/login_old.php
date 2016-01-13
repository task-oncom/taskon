<script type="text/javascript">

$(function()
{
    var $pc_errors = $('#pc_errors');
    var $pc_email  = $('#pc_email');


    $('#ps_button').click(function()
    {
        $pc_errors.html('');

        $.post('/users/user/ChangePasswordRequest', {'email' : $pc_email.val()}, function(res)
        {
            if (res.done)
            {
                $('#green_ok').html(res.msg);
            }
            else
            {
                var errors_html = '';

                for (var i in res.errors)
                {
                    errors_html+= res.errors[i] + '<br/>';
                }

                $pc_errors.html(errors_html);
            }
        },
        'json'
        );
    });


    $('#forgot_link').click(function()
    {
        $('.errorMessage').remove();

        if ($('#forgotpswd').is(':visible'))
        {
            $('#forgotpswd').hide(400);
        }
        else
        {
            $('#forgotpswd').show(400);
        }
    });
});

</script>




<?php if (Yii::app()->user->hasFlash('acrivate_done')): ?>

	<?php echo $this->msg(Yii::app()->user->getFlash('acrivate_done'), 'ok'); ?>

<?php elseif (Yii::app()->user->hasFlash('change_password_done')): ?>

	<?php echo $this->msg(Yii::app()->user->getFlash('change_password_done'), 'ok'); ?>

<?php endif ?>


<?php echo $form->renderBegin(); ?>

<div class="green_login">
    <div class="top"></div>
    <div id="forgotpswd">
        <b><a class="dop_link" onclick="$('#forgotpswd').hide(400);">Свернуть</a></b><br />
        <div id="vost">
            <span style="font-size: 90%; color: #686868;">Для восстановления пароля введите в поле свой e-mail:</span>
            <input type="text" id="pc_email" class="border_for_login_form" /><br />
            <input type="button" value="Восстановить пароль" id="ps_button" /><br/><br/>
            <div class="red_error" id="pc_errors">
<!--                Данный e-mail адрес не был найден. Пожалуйста, уточните e-mail адрес или-->
<!--                свяжитесь с <a href="" onclick="ShowForm(); return false;">Администратором</a>-->
            </div>

            <div id="green_ok" style="color:green"></div>
        </div>
        <div id="erors"></div>
    </div>

    <?php
    $errors = array();

    if (isset($auth_error))
    {
        $errors[] = $auth_error;
    }

    foreach ($form->elements as $element)
    {
        $html = $element->renderError();
        if ($html)
        {
            $errors[] = $html;
        }
    }
    ?>


    <?php if ($errors): ?>
        <div id="autherr" style="display: block">
            <b><a class="dop_link" onclick="$('#autherr').hide(400); return false;">Свернуть</a></b><br />
            <div class="red_error"><?php echo implode('', $errors); ?></div>
        </div>
    <?php endif ?>

    <div class="center">
        <div class="clear"></div>

        <table width="100%" border="0" class="auth_table">
            <tbody>
                <tr>
                    <td width="50" height="30" class="first_col" align="left"><b>Email</b></td>
                    <td align="right" height="30" class="second_col">
                        <?php echo $form->elements['email']->renderInput(); ?>
                    </td>
                </tr>
                <tr>
                    <td class="first_col" height="30" align="left"><b>Пароль</b></td>
                    <td class="second_col" height="30" align="right">
                        <table width="100%" class="auth_table" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td height="30" align="left">
                                        <?php echo $form->elements['password']->renderInput(); ?> &nbsp;
                                    </td>
                                    <td height="30">
                                        <a id="forgot_link" class="forgot_link">Забыли?</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="first_col" align="right" height="30"></td>
                    <td class="second_col" height="30">
                        <table width="100%" cellspacing="0" cellpadding="0" class="auth_table">
                            <tbody>
                                <tr>
                                    <td align="left" height="30">
                                        <input type="checkbox" value="1" name="User[forget]" id="check" /> &nbsp;&nbsp;
                                        <label for="check"><b>Чужой компьютер</b></label>
                                    </td>
                                    <td height="30">
                                        <?php echo $form->buttons['submit']->render(); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<?php echo $form->renderEnd(); ?>