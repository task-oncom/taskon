<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_094005_fix_language extends Migration
{
    public function safeUp()
    { 
        $this->createTable('languages', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(2) NOT NULL', 
            'codeFull' => Schema::TYPE_STRING . '(5) NOT NULL',
            'name' => Schema::TYPE_STRING . '(15) NOT NULL',      
        ]);

        $this->addForeignKey(
            'fk_meta_tags_lang_id_languages_id',
            'meta_tags', 'lang_id',
            'languages', 'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_meta_tags_lang_id_languages_id', 'meta_tags');
        
        $this->dropTable('languages');
    }
}
