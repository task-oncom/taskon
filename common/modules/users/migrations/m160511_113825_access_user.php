<?php

use yii\db\Migration;

class m160511_113825_access_user extends Migration
{
    public function up()
    {
        $this->insert('auth_item', [
            'name' => 'rbac',
            'type' => 2,
            'description' => 'Управление доступом',
        ]);

        $this->insert('auth_assignment', [
            'item_name' => 'rbac',
            'user_id' => 1,
        ]);
    }

    public function down()
    {
        $this->delete('auth_item', [
            'name' => 'rbac'
        ]);

        $this->delete('auth_assignment', [
            'item_name' => 'rbac',
            'user_id' => 1,
        ]);
    }
}
