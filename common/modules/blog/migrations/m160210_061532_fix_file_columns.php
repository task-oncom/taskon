<?php

use yii\db\Schema;
use yii\db\Migration;

class m160210_061532_fix_file_columns extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->alterColumn('posts', 'preview', Schema::TYPE_STRING.'(100) DEFAULT NULL');
    }

    public function safeDown()
    {
        $this->alterColumn('posts', 'preview', Schema::TYPE_STRING.'(60) DEFAULT NULL');
    }
}
