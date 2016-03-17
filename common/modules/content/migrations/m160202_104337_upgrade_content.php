<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_104337_upgrade_content extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE `co_blocks` ENGINE = InnoDB;')->execute();
        Yii::$app->db->createCommand('ALTER TABLE `co_blocks` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;')->execute();

        $this->createTable(
            'co_content_lang',
            [
                'id' => Schema::TYPE_PK,
                'content_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
                'lang_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
                'name' => Schema::TYPE_STRING.'(250) NOT NULL',
                'title' => Schema::TYPE_STRING.'(250) NOT NULL',
                'text' => 'longtext DEFAULT NULL',
            ]
        );

        $this->addForeignKey(
            'fk_content_lang_lang_id_languages_id',
            'co_content_lang', 'lang_id',
            'languages', 'id'
        );

        $this->addForeignKey(
            'fk_content_lang_content_id_content_id',
            'co_content_lang', 'content_id',
            'co_content', 'id'
        );

        $this->createTable(
            'co_blocks_lang',
            [
                'id' => Schema::TYPE_PK,
                'block_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
                'lang_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
                'name' => Schema::TYPE_STRING.'(50) NOT NULL',
                'title' => Schema::TYPE_STRING.'(250) NOT NULL',
                'text' => 'longtext DEFAULT NULL',
            ]
        );

        $this->addForeignKey(
            'fk_blocks_lang_lang_id_languages_id',
            'co_blocks_lang', 'lang_id',
            'languages', 'id'
        );

        $this->addForeignKey(
            'fk_blocks_lang_blocks_id_blocks_id',
            'co_blocks_lang', 'block_id',
            'co_blocks', 'id'
        );

        $this->dropColumn('co_blocks', 'date_create');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_content_lang_lang_id_languages_id', 'co_content_lang');
        $this->dropForeignKey('fk_content_lang_content_id_content_id', 'co_content_lang');
        $this->dropForeignKey('fk_blocks_lang_lang_id_languages_id', 'co_blocks_lang');
        $this->dropForeignKey('fk_blocks_lang_blocks_id_blocks_id', 'co_blocks_lang');
        $this->dropTable('co_content_lang');
        $this->dropTable('co_blocks_lang');

        $this->addColumn('co_blocks', 'date_create', Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }
}
