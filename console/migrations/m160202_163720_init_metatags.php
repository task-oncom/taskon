<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_163720_init_metatags extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('meta_tags', [
            'id' => Schema::TYPE_PK,
            'object_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'model_id' => Schema::TYPE_STRING . '(50) NOT NULL',
            'language' => Schema::TYPE_STRING . '(5) NOT NULL',
            'title' => Schema::TYPE_STRING . '(300) NOT NULL',
            'keywords' => Schema::TYPE_STRING . '(300) NOT NULL',
            'description' => Schema::TYPE_STRING . '(300) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
        ]);

        $this->createIndex('unique_object_id_model_id', 'meta_tags', ['object_id', 'model_id'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('unique_object_id_model_id', 'meta_tags');

        $this->dropTable('meta_tags');
    }
}
