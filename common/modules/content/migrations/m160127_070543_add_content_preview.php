<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_070543_add_content_preview extends Migration
{
    public function safeUp()
    {
        $this->addColumn('co_content', 'preview', Schema::TYPE_STRING . '(50) AFTER `active`');
    }

    public function safeDown()
    {
        $this->dropColumn('co_content', 'preview');
    }
}
