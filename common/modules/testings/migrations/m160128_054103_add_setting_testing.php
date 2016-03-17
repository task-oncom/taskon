<?php

use yii\db\Migration;

class m160128_054103_add_setting_testing extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->insert('settings', [
            'module_id' => 'testings',
            'code' => 'testings_questions_with_picture',
            'name' => 'Ключевые слова для загрузки картинки в вопрос',
            'value' => 'карт, изобр, изображение, скриншот, картинка, схема, рисунок, рис',
            'element' => 'text',
            'hidden' => 0,
            'description' => 'Ключевые слова для загрузки картинки в вопрос',
        ]);
    }

    public function safeDown()
    {
        $this->delete('settings', ['code' => 'testings_questions_with_picture']);
    }
}


