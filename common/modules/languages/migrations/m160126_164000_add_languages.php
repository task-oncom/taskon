<?php

use yii\db\Migration;

class m160126_164000_add_languages extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->insert('languages', [
            'code' => 'ru',
            'codeFull' => 'rus',
            'name' => 'Русский',
        ]);

        $this->insert('languages', [
            'code' => 'en',
            'codeFull' => 'eng',
            'name' => 'English',
        ]);
    }

    public function safeDown()
    {
        $this->delete('languages', ['code' => 'ru']);
        $this->delete('languages', ['code' => 'en']);
    }
}
