<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_090228_create_bids extends Migration
{
    public function up()
    {
        $this->createTable('bids', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(100) DEFAULT NULL',
            'phone' => Schema::TYPE_STRING . '(30) DEFAULT NULL',
            'email' => Schema::TYPE_STRING . '(70) DEFAULT NULL',
            'filename' => Schema::TYPE_STRING . '(50) DEFAULT NULL',
            'text' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'created_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . '(11) NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('bids');
    }
}
