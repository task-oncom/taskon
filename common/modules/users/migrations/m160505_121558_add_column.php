<?php

use yii\db\Migration;

class m160505_121558_add_column extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'last_logon', $this->integer(11));
    }

    public function down()
    {
        $this->dropColumn('users', 'last_logon', $this->integer(11));
    }
}
