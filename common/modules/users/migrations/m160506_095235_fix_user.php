<?php

use yii\db\Migration;

class m160506_095235_fix_user extends Migration
{
    public function up()
    {
        $this->alterColumn('users', 'password_change_code', $this->string(255));
    }

    public function down()
    {
        $this->alterColumn('users', 'password_change_code', $this->string(32));
    }
}
