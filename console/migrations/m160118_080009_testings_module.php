<?php

use yii\db\Schema;
use yii\db\Migration;

class m160118_080009_testings_module extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') 
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        // Структура таблицы `testings_answers`
 
        $this->createTable('testings_answers', [
            'id' => Schema::TYPE_PK,
            'question_id' => Schema::TYPE_INTEGER . ' NOT NULL', 
            'text' => Schema::TYPE_TEXT . ' NOT NULL',
            'is_right' => 'tinyint(1) NOT NULL',
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',      
        ], $tableOptions);

        // Структура таблицы `testings_themes`

        $this->createTable('testings_themes', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(200) NOT NULL', 
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL', 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 
        ], $tableOptions);

        // Структура таблицы `testings_mistakes`

        $this->createTable('testings_mistakes', [
            'id' => Schema::TYPE_PK,
            'passing_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'description' => Schema::TYPE_STRING . '(3000) NOT NULL', 
            'is_expert_agreed' => 'tinyint(1) NOT NULL', 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 
        ], $tableOptions);

        // Структура таблицы `testings_passings`

        $this->createTable('testings_passings', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'test_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'is_passed' => 'tinyint(1) DEFAULT NULL', 
            'percent_rights' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'attempt' => Schema::TYPE_INTEGER . '(3) NOT NULL', 
            'pass_date' => Schema::TYPE_STRING . '(20) DEFAULT NULL', 
            'pass_date_start' => Schema::TYPE_STRING . '(22) NOT NULL', 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', 
            'end_date' => Schema::TYPE_STRING . '(22) DEFAULT NULL', 
        ], $tableOptions);

        // Структура таблицы `testings_questions`

        $this->createTable('testings_questions', [
            'id' => Schema::TYPE_PK,
            'test_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'theme_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'text' => Schema::TYPE_TEXT . ' NOT NULL', 
            'type' => Schema::TYPE_INTEGER . '(5) NOT NULL', 
            'is_active' => 'tinyint(1) NOT NULL', 
            'author' => Schema::TYPE_STRING . '(100) NOT NULL', 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        // Структура таблицы `testings_questions_passings`

        $this->createTable('testings_questions_passings', [
            'id' => Schema::TYPE_PK,
            'passing_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'question_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL', 
            'question_text' => Schema::TYPE_TEXT . ' NOT NULL', 
            'answer_text' => Schema::TYPE_TEXT . ' NOT NULL', 
            'user_answer' => Schema::TYPE_TEXT . ' NOT NULL', 
            'answer_time' => Schema::TYPE_INTEGER . '(5) DEFAULT NULL',  
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        // Структура таблицы `testings_sessions`

        $this->createTable('testings_sessions', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(80) NOT NULL', 
            'start_date' => Schema::TYPE_STRING . '(20) NOT NULL', 
            'end_date' => Schema::TYPE_STRING . '(20) NOT NULL', 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        // Структура таблицы `testings_tests`

        $this->createTable('testings_tests', [
            'id' => Schema::TYPE_PK,
            'session_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'name' => Schema::TYPE_STRING . '(200) NOT NULL', 
            'minutes' => Schema::TYPE_INTEGER . '(5) NOT NULL', 
            'questions' => Schema::TYPE_INTEGER . '(5) NOT NULL', 
            'pass_percent' => Schema::TYPE_INTEGER . '(5) NOT NULL', 
            'attempt' => Schema::TYPE_INTEGER . '(3) NOT NULL', 
            'mix' => Schema::TYPE_INTEGER . '(1) NOT NULL', 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        // Структура таблицы `testings_users`

        $this->createTable('testings_users', [
            'id' => Schema::TYPE_PK,
            'sex' => 'tinyint(1) NOT NULL', 
            'first_name' => Schema::TYPE_STRING . '(50) NOT NULL', 
            'patronymic' => Schema::TYPE_STRING . '(50) DEFAULT NULL', 
            'last_name' => Schema::TYPE_STRING . '(50) NOT NULL', 
            'company_name' => Schema::TYPE_STRING . '(250) NOT NULL', 
            'city' => Schema::TYPE_STRING . '(100) DEFAULT NULL', 
            'login' => Schema::TYPE_STRING . '(30) NOT NULL', 
            'password' => Schema::TYPE_STRING . '(30) NOT NULL', 
            'email' => Schema::TYPE_STRING . '(150) NOT NULL', 
            'manager_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL', 
            'tki' => Schema::TYPE_STRING . '(150) DEFAULT NULL', 
            'region' => Schema::TYPE_STRING . '(100) DEFAULT NULL', 
            'is_auth' => "tinyint(1) DEFAULT '0'", 
            'create_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        // Структура таблицы `testings_users_groups`

        $this->createTable('testings_users_groups', [
            'id' => Schema::TYPE_PK,
            'session_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'name' => Schema::TYPE_STRING . '(255) DEFAULT NULL', 
            'created' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        // Структура таблицы `testings_users_groups_assign`

        $this->createTable('testings_users_groups_assign', [
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'group_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'session_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
        ], $tableOptions);

        // Структура таблицы `testings_users_history`

        $this->createTable('testings_users_history', [
            'id' => Schema::TYPE_PK,
            'session_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'user_id' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'email' => Schema::TYPE_STRING . '(255) DEFAULT NULL', 
            'sended' => Schema::TYPE_INTEGER . '(11) NOT NULL', 
            'file' => Schema::TYPE_STRING . '(255) DEFAULT NULL', 
            'unisender_email' => Schema::TYPE_STRING . '(255) DEFAULT NULL', 
            'unisender_status' => Schema::TYPE_STRING . '(20) DEFAULT NULL', 
            'notified' => Schema::TYPE_INTEGER . '(1) NOT NULL', 
            'created' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->createIndex('testings_answers2testings_questions', 'testings_answers', 'question_id');
        $this->createIndex('testings_mistakes2testings_passings', 'testings_mistakes', 'passing_id');
        $this->createIndex('testings_passings2testings_users', 'testings_passings', 'user_id');
        $this->createIndex('testings_passings2tests', 'testings_passings', 'test_id');
        $this->createIndex('is_passed', 'testings_passings', 'is_passed');
        $this->createIndex('pass_date', 'testings_passings', 'pass_date');
        $this->createIndex('create_date', 'testings_passings', 'create_date');
        $this->createIndex('end_date', 'testings_passings', 'end_date');
        $this->createIndex('testings_questions2testings_tests', 'testings_questions', 'test_id');
        $this->createIndex('testings_questions2testings_gammas', 'testings_questions', 'theme_id');
        $this->createIndex('testings_questions_passings2testings_passings', 'testings_questions_passings', 'passing_id');
        $this->createIndex('testings_questions_passings2testings_questions', 'testings_questions_passings', 'question_id');
        $this->createIndex('name', 'testings_sessions', 'name');
        $this->createIndex('start_date', 'testings_sessions', 'start_date');
        $this->createIndex('end_date', 'testings_sessions', 'end_date');
        $this->createIndex('create_date', 'testings_sessions', 'create_date');
        $this->createIndex('testings_tests2testings_sessions', 'testings_tests', 'session_id');
        $this->createIndex('name', 'testings_tests', 'name');
        $this->createIndex('create_date', 'testings_tests', 'create_date');
        $this->createIndex('testings_users2users', 'testings_users', 'manager_id');
    }

    public function safeDown()
    {
        $this->dropTable('testings_answers');
        $this->dropTable('testings_themes');
        $this->dropTable('testings_mistakes');
        $this->dropTable('testings_passings');
        $this->dropTable('testings_questions');
        $this->dropTable('testings_questions_passings');
        $this->dropTable('testings_sessions');
        $this->dropTable('testings_tests');
        $this->dropTable('testings_users');
        $this->dropTable('testings_users_groups');
        $this->dropTable('testings_users_groups_assign');
        $this->dropTable('testings_users_history');
    }
}
