<?php

use yii\db\Schema;
use yii\db\Migration;

class m160119_094005_fix_language extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Структура таблицы `languages`
 
        $this->createTable('languages', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(2) NOT NULL', 
            'codeFull' => Schema::TYPE_STRING . '(5) NOT NULL',
            'name' => Schema::TYPE_STRING . '(15) NOT NULL',      
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('languages');
    }
}
