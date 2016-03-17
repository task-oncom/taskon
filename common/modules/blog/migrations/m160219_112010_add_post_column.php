<?php

use yii\db\Schema;
use yii\db\Migration;

class m160219_112010_add_post_column extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('posts', 'author_id', Schema::TYPE_INTEGER . '(11) NOT NULL AFTER `active`');
    }

    public function safeDown()
    {
        $this->dropColumn('posts', 'author_id');
    }
}
