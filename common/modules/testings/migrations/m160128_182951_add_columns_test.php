<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_182951_add_columns_test extends Migration
{
    public function safeUp()
    {
        $this->addColumn('testings_tests', 'passed_scores', Schema::TYPE_INTEGER . '(11) NOT NULL');
        $this->addColumn('testings_tests', 'notpassed_scores', Schema::TYPE_INTEGER . '(11) NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('testings_tests', 'passed_scores');
        $this->dropColumn('testings_tests', 'notpassed_scores');
    }
}
