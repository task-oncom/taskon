<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_053816_add_column_posts extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('posts', 'preview', Schema::TYPE_STRING.'(60) DEFAULT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('posts', 'preview');
    }
}
