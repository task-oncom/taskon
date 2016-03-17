<?php

use yii\db\Migration;

class m160202_173116_fix_index_meta_tags extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropIndex('unique_object_id_model_id', 'meta_tags');
        $this->createIndex('unique_object_model_lang', 'meta_tags', ['object_id', 'lang_id', 'model_id'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('unique_object_model_lang', 'meta_tags');
        $this->createIndex('unique_object_id_model_id', 'meta_tags', ['object_id', 'model_id'], true);
    }
}
