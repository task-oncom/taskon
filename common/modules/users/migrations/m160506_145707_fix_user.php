<?php

use yii\db\Migration;

class m160506_145707_fix_user extends Migration
{
    public function up()
    {
        $this->alterColumn('users', 'phone', $this->string(20));
        $this->alterColumn('users', 'mobile_phone', $this->string(20));
    }

    public function down()
    {
        $this->alterColumn('users', 'phone', $this->string(14));
        $this->alterColumn('users', 'mobile_phone', $this->string(14));
    }
}
