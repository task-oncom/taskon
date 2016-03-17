<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_143445_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('co_blocks', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'lang' => "char(2) DEFAULT 'ru'",
            'module' => Schema::TYPE_STRING . '(100) DEFAULT NULL',
            'title' => Schema::TYPE_STRING . '(250) NOT NULL',
            'name' => Schema::TYPE_STRING . '(50) NOT NULL',
            'text' => 'longtext NOT NULL',
            'date_create' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->createIndex('lang_2', 'co_blocks', ['lang', 'title'], true);
        $this->createIndex('lang_3', 'co_blocks', ['lang', 'name'], true);
        $this->createIndex('lang', 'co_blocks', ['lang']);

        $this->createTable('co_category', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->insert('co_category', [
            'name' => 'Главная страница',
            'url' => '1'
        ]);

        $this->createTable('co_content', [
            'id' => Schema::TYPE_PK,
            'category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'url' => Schema::TYPE_STRING . '(250) NOT NULL',
            'name' => Schema::TYPE_STRING . '(250) NOT NULL',
            'title' => Schema::TYPE_STRING . '(250) NOT NULL',
            'text' => 'longtext',
            'active' => Schema::TYPE_INTEGER . '(1) DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->createIndex('category', 'co_content', ['category_id']);

        $this->insert('co_content', [
            'url' => 'site/error',
            'name' => '404',
            'title' => '404',
            'text' => 'test',
            'active' => 1,
        ]);

        $this->insert('co_content', [
            'url' => '/',
            'name' => 'Главная',
            'title' => 'Главная',
            'text' => 'Главная',
            'active' => 1,
        ]);

        $this->addForeignKey(
            'fk_co_content_category_id_co_category_id',
            'co_content', 'category_id',
            'co_category', 'id'
        );

        // Settings

        $this->insert('settings', [
            'module_id' => 'content',
            'code' => 'content-phone',
            'name' => 'Телефонный номер',
            'value' => 'test',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Телефонный номер',
        ]);

        $this->insert('settings', [
            'module_id' => 'content',
            'code' => 'content-email',
            'name' => 'Контактный e-mail адрес',
            'value' => 'test@test.com',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Контактный e-mail адрес',
        ]);

        $this->insert('settings', [
            'module_id' => 'content',
            'code' => 'content-support-email',
            'name' => 'E-mail службы поддержки - отображается в отправляемых e-mail письмах',
            'value' => 'test@test.com',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'E-mail службы поддержки - отображается в отправляемых e-mail письмах',
        ]);
    }

    public function safeDown()
    {
        $this->dropIndex('lang_2', 'co_blocks');
        $this->dropIndex('lang_3', 'co_blocks');
        $this->dropIndex('lang', 'co_blocks');

        $this->dropTable('co_blocks');

        $this->dropTable('co_category');

        $this->dropIndex('category', 'co_content');

        $this->dropTable('co_content');

        $this->dropForeignKey('fk_co_content_category_id_co_category_id', 'co_content');

        // Settings
        
        $this->delete('settings', [
            'code' => 'content-phone',
            'module_id' => 'content',
        ]);

        $this->delete('settings', [
            'code' => 'content-email',
            'module_id' => 'content',
        ]);

        $this->delete('settings', [
            'code' => 'content-support-email',
            'module_id' => 'content',
        ]);
    }
}