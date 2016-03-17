<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_151440_add_table_blog extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('posts', [
            'id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_STRING.'(255) NOT NULL',
            'active' => Schema::TYPE_INTEGER.'(1) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
        ]);

        $this->createIndex('posts_url_unique', 'posts', 'url', true);

        $this->createTable('posts_lang', [
            'id' => Schema::TYPE_PK,
            'post_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'lang_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'title' => Schema::TYPE_STRING.'(255) DEFAULT NULL',
            'text' => 'longtext DEFAULT NULL',
            'created_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
        ]);

        $this->createTable('posts_tags', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING.'(255) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
        ]);

        $this->createIndex('posts_tags_index', 'posts_tags', 'name');

        $this->createTable('posts_tags_assign', [
            'id' => Schema::TYPE_PK,
            'tag_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'post_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
            'updated_at' => Schema::TYPE_INTEGER.'(11) DEFAULT NULL',
        ]);

        $this->createIndex('posts_tags_assign_tag_id_post_id_unique', 'posts_tags_assign', ['tag_id', 'post_id'], true);

        $this->addForeignKey(
            'fk_posts_lang_lang_id_languages_id',
            'posts_lang', 'lang_id',
            'languages', 'id'
        );

        $this->addForeignKey(
            'fk_posts_lang_posts_id_post_id',
            'posts_lang', 'post_id',
            'posts', 'id'
        );

        $this->addForeignKey(
            'fk_posts_tags_assign_tag_id_posts_tags_id',
            'posts_tags_assign', 'tag_id',
            'posts_tags', 'id'
        );

        $this->addForeignKey(
            'fk_posts_tags_assign_post_id_posts_id',
            'posts_tags_assign', 'post_id',
            'posts', 'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_posts_lang_lang_id_languages_id', 'posts_lang');
        $this->dropForeignKey('fk_posts_lang_posts_id_post_id', 'posts_lang');

        $this->dropForeignKey('fk_posts_tags_assign_tag_id_posts_tags_id', 'posts_tags_assign');
        $this->dropForeignKey('fk_posts_tags_assign_post_id_posts_id', 'posts_tags_assign');

        $this->dropIndex('posts_tags_index', 'posts_tags');
        $this->dropIndex('posts_url_unique', 'posts');
        $this->dropIndex('posts_tags_assign_tag_id_post_id_unique', 'posts_tags_assign');

        $this->dropTable('posts');
        $this->dropTable('posts_lang');
        $this->dropTable('posts_tags');
        $this->dropTable('posts_tags_assign');
    }
}
