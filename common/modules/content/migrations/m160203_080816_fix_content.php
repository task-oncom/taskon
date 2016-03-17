<?php

use yii\db\Schema;
use yii\db\Migration;

class m160203_080816_fix_content extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropColumn('co_blocks_lang', 'name');
        $this->dropColumn('co_blocks_lang', 'title');

        $this->dropColumn('co_blocks', 'lang');
        $this->dropColumn('co_blocks', 'module');

        $this->dropColumn('co_content', 'name');
        $this->dropColumn('co_content', 'title');
        $this->dropColumn('co_content', 'text');
    }

    public function safeDown()
    {
        $this->addColumn('co_blocks_lang', 'name', Schema::TYPE_STRING.'(50) NOT NULL');
        $this->addColumn('co_blocks_lang', 'title', Schema::TYPE_STRING.'(250) NOT NULL');

        $this->addColumn('co_blocks', 'lang', "char(2) DEFAULT 'ru'");
        $this->addColumn('co_blocks', 'module', Schema::TYPE_STRING.'(100) NOT NULL');

        $this->addColumn('co_content', 'name', Schema::TYPE_STRING.'(250) NOT NULL');
        $this->addColumn('co_content', 'title', Schema::TYPE_STRING.'(250) NOT NULL');
        $this->addColumn('co_content', 'text', 'longtext DEFAULT NULL');
    }
}
