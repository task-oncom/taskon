<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_123717_add_column_co_content extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('co_content', 'custom', Schema::TYPE_STRING . '(20) DEFAULT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('co_content', 'custom');
    }
}
