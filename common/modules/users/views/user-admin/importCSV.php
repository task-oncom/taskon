<style type="text/css">
    .old_value {
        text-decoration: line-through;
        color: #990000;
    }
</style>


<?php if (isset($form)): ?>

    <?php if (Yii::app()->user->hasFlash('import_done')): ?>
        <?php echo $this->msg(Yii::app()->user->getFlash('import_done'), 'ok'); ?>
    <?php endif ?>

    <span style="font-size: 14px; color: #008C66;">Краткая инструкция по файла реестра пользователей</span><br /><br />
    <div>
    Для загрузки реестра пользователей на сайт необходимо:
    <ol>
       <ul>1. заполнить <a href="/upload/users/users.xls">шаблон</a> в формате MS Excel. Поля для назначения тестов имеют формат "да/нет".</ul>
       <ul>2. сохранить файл как CSV (разделители-запятые)</ul>

       <ul>3. выбрать группу пользователей из раскрывающегося списка выше</ul>
       <ul>4. загрузить файл на сайт c помощью кнопки ниже</ul>
    </ol>
    <span style="color: red;">Важно!</span> Не используйте клавишу ENTER для перевода строки при заполнении шаблона. Если это необходимо, пользуйтесь вместо этого тегом <strong><span style="color: red">&lt;br&gt;</span></strong>.

    <?php echo $form; ?>

<?php endif ?>



<?php if (isset($users)): ?>

    <form method="post">
        <input type="hidden" name="role" value="<?php echo $_POST['User']['role'] ?>" />
        <input type="hidden" name="send_email" value="<?php echo $_POST['User']['send_email'] ?>" />
        <?php
        $model = User::model();
        $model->scenario = User::SCENARIO_CSV_IMPORT;
        ?>


        <?php foreach ($users as $i => $user): ?>

            <?php
            $user["password"] = isset($user["password"]) ? $user["password"] : PasswordGenerator::generate(6);

            $object = $model->findByAttributes(array('login' => trim($user['login'])));

            if ($object)
            {
                foreach ($object->attributes as $attr => $value)
                {
                    if (isset($user[$attr]) && ($user[$attr] != $value))
                    {
                        if ($attr == 'password')
                        {
                            $user[$attr] = trim($user[$attr]);
                            if (empty($user[$attr]))
                            {
                                $object->$attr = null;
                                continue;
                            }

                            if (md5($user[$attr]) != $object->$attr)
                            {
                                $object->$attr = "<div class='old_value'>{$value}</div> <br/> {$user[$attr]}";
                            }
                            else
                            {
                                $object->$attr = $user[$attr];
                            }
                        }
                        else if ($attr == 'city_id')
                        {
                            if ($object->city)
                            {
                                if ($user[$attr] && $user[$attr] != $object->city->name)
                                {
                                    $object->$attr = "<div class='old_value'>{$object->city->name}</div> <br/> {$user[$attr]}";
                                }
                                else
                                {
                                    $object->$attr = $object->city->name;
                                }
                            }
                        }
                        else
                        {
                            $object->$attr = "<div class='old_value'>{$value}</div><br/> {$user[$attr]}";
                        }
                    }
                }
            }
            else
            {
                $object = new User;
                $object->attributes = $user;
                $object->password   = $user["password"];
            }

            $checked = 'checked';

            ?>


            <h3 style="color: <?php echo $object->isNewRecord ? 'green' : 'orange' ?>">
                <input type="checkbox" name='users[<?php echo $i; ?>][checked]'<?php echo $object->isNewRecord ? $checked : ''; ?>> &nbsp;
                <?php echo $object->isNewRecord ? 'Будет добавлено' : 'Будет отредактировано'; ?>
            </h3>

            <?php
            $this->widget('application.components.DetailView', array(
                'data' => $object,
                'attributes' => array(
                    'last_name:raw',
                    'first_name:raw',
                    'patronymic:raw',
                    'email:raw',
                    'login:raw',
                    'password:raw',
                    'phone:raw',
                    'phone_ext:raw',
                    'fax:raw',
                    array('name' => 'city_id', 'type' => 'raw', 'value' => isset($model->city) ? $model->city->name : null),
                    'company:raw',
                    'post:raw',
                    'address:raw',
                ),
            ));

            ?>

            <?php foreach ($user as $label => $value): ?>
                <input type='hidden' name='users[<?php echo $i; ?>][<?php echo $label; ?>]' value='<?php echo $value; ?>' />
            <?php endforeach ?>

            <br/>

            <?php endforeach ?>

        <input type="submit" value="Обновить" class="submit mid" />
    </form>

<?php endif ?>
