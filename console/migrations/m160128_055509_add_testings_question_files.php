<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_055509_add_testings_question_files extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'testings_questions_image',
            [
                'id' => Schema::TYPE_PK,
                'question_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
                'filename' => Schema::TYPE_STRING.'(50) NOT NULL',
            ]
        );
        $this->addForeignKey(
            'fk_questions_image_question_id_question_id',
            'testings_questions_image', 'question_id',
            'testings_questions', 'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_questions_image_question_id_question_id', 'testings_questions_image');
        $this->dropTable('testings_questions_image');
    }
}
