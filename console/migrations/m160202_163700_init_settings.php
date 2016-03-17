<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_163700_init_settings extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('settings', [
            'id' => Schema::TYPE_PK,
            'module_id' => Schema::TYPE_STRING . '(50) NOT NULL',
            'code' => Schema::TYPE_STRING . '(50) NOT NULL',
            'name' => Schema::TYPE_STRING . '(100) NOT NULL',
            'value' => 'text NOT NULL',
            'element' => "enum('text','textarea','editor') NOT NULL",
            'hidden' => 'tinyint(1) NOT NULL DEFAULT 0',
            'description' => Schema::TYPE_STRING . '(2550) NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->createIndex('unique_code', 'settings', ['code'], true);
        $this->createIndex('unique_name', 'settings', ['name'], true);        
    }

    public function safeDown()
    {
        $this->dropIndex('unique_code', 'settings');
        $this->dropIndex('unique_name', 'settings');

        $this->dropTable('settings');
    }
}