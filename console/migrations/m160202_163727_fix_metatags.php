<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_163727_fix_metatags extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->renameColumn('meta_tags', 'language', 'lang_id');
        $this->alterColumn('meta_tags', 'lang_id', Schema::TYPE_INTEGER.'(11) DEFAULT NULL');
    }

    public function safeDown()
    {
        $this->renameColumn('meta_tags', 'lang_id', 'language');
        $this->alterColumn('meta_tags', 'language', Schema::TYPE_STRING.'(5) NOT NULL');
    }
}
