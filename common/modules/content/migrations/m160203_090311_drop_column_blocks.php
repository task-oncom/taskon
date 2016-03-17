<?php

use yii\db\Migration;

class m160203_090311_drop_column_blocks extends Migration
{
    public function up()
    {
        $this->dropColumn('co_blocks', 'text');
    }

    public function down()
    {
        $this->createColumn('co_blocks', 'text', 'longtext NOT NULL');
    }
}
