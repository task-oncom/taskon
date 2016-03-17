<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_084445_addcolumn_bids extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('bids', 'form', Schema::TYPE_STRING . '(50) DEFAULT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('bids', 'form');
    }
}
