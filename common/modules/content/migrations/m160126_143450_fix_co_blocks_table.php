<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_143450_fix_co_blocks_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('co_blocks', 'category_id', Schema::TYPE_INTEGER . '(11) NULL');
    }

    public function safeDown()
    {
        $this->alterColumn('co_blocks', 'category_id', Schema::TYPE_INTEGER . '(11) NOT NULL');
    }
}
