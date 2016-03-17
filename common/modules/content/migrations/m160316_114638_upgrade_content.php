<?php

use yii\db\Schema;
use yii\db\Migration;

class m160316_114638_upgrade_content extends Migration
{
    public function up()
    {
        $this->addColumn('co_content', 'type', Schema::TYPE_INTEGER . '(1) NOT NULL DEFAULT 1');
    }

    public function down()
    {
        $this->dropColumn('co_content', 'type');
    }
}
