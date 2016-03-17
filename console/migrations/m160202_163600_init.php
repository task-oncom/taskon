<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_163600_init extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password' => Schema::TYPE_STRING . '(62) DEFAULT NULL',
            'auth_key' => $this->string(32)->notNull(),
            'fio' => $this->string(200),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'phone' => Schema::TYPE_STRING . '(14) DEFAULT NULL',
            'mobile_phone' => Schema::TYPE_STRING . '(14) DEFAULT NULL',
            'skype' => Schema::TYPE_STRING . '(20) DEFAULT NULL',
            'status' => "enum('active','new','blocked') DEFAULT 'new'",
            'activate_code' => Schema::TYPE_STRING . '(32) DEFAULT NULL',
            'activate_date' => 'datetime DEFAULT NULL',
            'password_change_code' => Schema::TYPE_STRING . '(32) DEFAULT NULL',
            'password_change_date' => 'datetime DEFAULT NULL',
            'is_deleted' => 'tinyint(1) NOT NULL DEFAULT 0',
            'date_delete' => 'datetime DEFAULT NULL',
            'date_create' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'ICQ' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL',
            'post' => Schema::TYPE_STRING . '(60) DEFAULT NULL',
            'sort' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL',
            'role' => Schema::TYPE_STRING . '(150) DEFAULT NULL',
        ]);

        $this->createIndex('unique_email', 'users', ['email'], true);

        $this->insert('users', [
            'email' => 'admin@task-on.com',
            'password' => '$2y$13$9zAfvKebaL2DFOOjMPFHg.JhP3ebpScdMa1iG7YhzfYi0nXcGI3.O',
            'auth_key' => 'Cae8WmdkwECBOKIODDZ5OfQHvN-8JmPn',
            'fio' => 'Разработчик',
            'status' => "active",
            'post' => 'Разработчик',
            'role' => 'admin',
        ]);
    }

    public function down()
    {
        $this->dropIndex('unique_email', 'users');

        $this->dropTable('users');
    }
}