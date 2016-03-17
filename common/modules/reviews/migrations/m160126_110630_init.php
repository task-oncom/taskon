<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_110630_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('reviews', [
            'id' => Schema::TYPE_PK,
            'lang' => "char(2) DEFAULT 'ru'",
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'title' => Schema::TYPE_STRING . '(250) NOT NULL',
            'text' => 'longtext NOT NULL',
            'good' => 'longtext',
            'bad' => 'longtext',
            'admin_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'answer' => 'longtext',
            'photo' => Schema::TYPE_STRING . '(50) DEFAULT NULL',
            'state' => "enum('active','hidden') NOT NULL",
            'date' => "date NOT NULL",
            'date_create' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'priority' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'email' => Schema::TYPE_STRING . '(255) NOT NULL',
            'notification_date' => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'notification_send' => "tinyint(1) NOT NULL",
            'order' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'attendant_products' => 'text NOT NULL',
            'cat_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'show_in_module' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'rate_usability' => Schema::TYPE_INTEGER . '(1) DEFAULT 0',
            'rate_loyality' => Schema::TYPE_INTEGER . '(1) DEFAULT 0',
            'rate_profit' => Schema::TYPE_INTEGER . '(1) DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->createIndex('lang', 'reviews', ['lang']);
        $this->createIndex('user_id', 'reviews', ['user_id']);

        $this->insert('settings', [
            'module_id' => 'reviews',
            'code' => 'email-new-review',
            'name' => 'Адрес для e-mail уведомлений о поступлении нового отзыва на модерацию',
            'value' => 'test@test.com',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Адрес для e-mail уведомлений о поступлении нового отзыва на модерацию',
        ]);
    }

    public function safeDown()
    {
        $this->dropIndex('lang', 'reviews');
        $this->dropIndex('user_id', 'reviews');

        $this->dropTable('reviews');

        $this->delete('settings', [
            'code' => 'email-new-review',
            'module_id' => 'reviews',
        ]);
    }
}