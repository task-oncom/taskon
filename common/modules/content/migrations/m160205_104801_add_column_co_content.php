<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_104801_add_column_co_content extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('co_content', 'priority', "FLOAT( 3, 1 ) DEFAULT '0.8' AFTER url");
    }

    public function safeDown()
    {
        $this->dropColumn('co_content', 'priority');
    }
}
