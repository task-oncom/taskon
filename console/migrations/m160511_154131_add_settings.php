<?php

use yii\db\Migration;

class m160511_154131_add_settings extends Migration
{
    public function up()
    {
        $this->update('settings', ['module_id' => 'main'], ['code' => 'content-support-email']);

        $this->insert('settings', [
            'module_id' => 'main',
            'code' => 'setting-info-email',
            'name' => 'E-mail для отправки уведомлений (данный e-mail будет использоваться в качестве отправителя)',
            'value' => 'info@task-on.com',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'E-mail для отправки уведомлений (данный e-mail будет использоваться в качестве отправителя)',
        ]);

        $this->insert('settings', [
            'module_id' => 'main',
            'code' => 'setting-from-email',
            'name' => 'Имя отправителя в E-mail письмах (данное имя будет отображаться в качестве отправителя)',
            'value' => 'Task-On',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Имя отправителя в E-mail письмах (данное имя будет отображаться в качестве отправителя)',
        ]);

        $this->insert('settings', [
            'module_id' => 'main',
            'code' => 'setting-project-name',
            'name' => 'Название проекта (отображается в письмах отправляемых сайтом)',
            'value' => 'Task-On',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Название проекта (отображается в письмах отправляемых сайтом)',
        ]);

        $this->insert('settings', [
            'module_id' => 'main',
            'code' => 'setting-project-url',
            'name' => 'URL адреса сайта (отображается в письмах отправляемых сайтом)',
            'value' => 'http://task-on.com',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'URL адреса сайта (отображается в письмах отправляемых сайтом)',
        ]);
    }

    public function down()
    {
        $this->update('settings', ['module_id' => 'content'], ['code' => 'content-support-email']);

        $this->delete('settings', ['code' => 'setting-info-email']);
        $this->delete('settings', ['code' => 'setting-from-email']);
        $this->delete('settings', ['code' => 'setting-project-name']);
        $this->delete('settings', ['code' => 'setting-project-url']);
    }
}
