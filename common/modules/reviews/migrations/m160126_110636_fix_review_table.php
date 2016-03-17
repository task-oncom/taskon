<?php

use yii\db\Schema;
use yii\db\Migration;

class m160126_110636_fix_review_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('reviews', 'video', Schema::TYPE_STRING . '(255) AFTER `photo`');
    }

    public function safeDown()
    {
        $this->dropColumn('reviews', 'video');
    }
}
