<?php

use yii\db\Schema;
use yii\db\Migration;

class m160317_121554_init extends Migration
{
    public function up()
    {
        $this->createTable('faq', [
            'id' => Schema::TYPE_PK,
            'lang' => Schema::TYPE_STRING . "(2) NOT NULL DEFAULT 'ru'",
            'name' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'last_name' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'patronymic' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'phone' => Schema::TYPE_STRING . '(50) DEFAULT NULL',
            'email' => Schema::TYPE_STRING . '(80) DEFAULT NULL',
            'cat_id' => Schema::TYPE_INTEGER . '(1) DEFAULT NULL',
            'question' => 'longtext',
            'answer' => 'longtext',
            'is_published' => "tinyint(1) DEFAULT 0",
            'welcome' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'notification_date' => "date DEFAULT NULL",
            'notification_send' => "tinyint(1) DEFAULT 0",
            'order' => Schema::TYPE_INTEGER . '(5) DEFAULT NULL',
            'show_in_module' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'view_count' => Schema::TYPE_INTEGER . '(11) DEFAULT 0',
            'url' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->createTable('faq_category', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->insert('faq_category', [
            'name' => 'Основная'
        ]);

        $this->insert('settings', [
            'module_id' => 'faq',
            'code' => 'email-publish-new-question',
            'name' => 'Адрес для e-mail уведомлений о публикации нового вопроса',
            'value' => '',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Адрес для e-mail уведомлений о публикации нового вопроса',
        ]);
    }

    public function down()
    {
        $this->dropTable('faq');

        $this->dropTable('faq_category');

        $this->delete('settings', [
            'code' => 'email-publish-new-question',
            'module_id' => 'faq',
        ]);
    }
}