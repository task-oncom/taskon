<?php

use yii\db\Schema;
use yii\db\Migration;

class m160317_131820_init extends Migration
{
    public function up()
    {
        $this->insert('auth_rule', [
            'name' => 'group',
            'data' => 'O:40:"common\modules\rbac\components\GroupRule":3:{s:4:"name";s:5:"group";s:9:"createdAt";i:1425110198;s:9:"updatedAt";i:1425110198;}',
        ]);

        $this->insert('auth_item', [
            'name' => 'admin',
            'type' => 2,
            'description' => 'Admin',
            'data' => 'group',
        ]);

        $this->insert('auth_item', [
            'name' => 'moderator',
            'type' => 2,
            'description' => 'Moderator',
            'data' => 'group',
        ]);

        $this->insert('auth_item', [
            'name' => 'superadmin',
            'type' => 2,
            'description' => 'Superadmin',
            'data' => 'group',
        ]);

        $this->insert('auth_item', [
            'name' => 'user',
            'type' => 2,
            'description' => 'User',
            'data' => 'group',
        ]);

        $this->insert('auth_item_child', [
            'parent' => 'superadmin',
            'child' => 'admin',
        ]);

        $this->insert('auth_item_child', [
            'parent' => 'admin',
            'child' => 'moderator',
        ]);

        $this->insert('auth_item_child', [
            'parent' => 'moderator',
            'child' => 'user',
        ]);

        $this->insert('auth_assignment', [
            'item_name' => 'superadmin',
            'user_id' => 1,
        ]);
    }

    public function down()
    {
        $this->delete('auth_rule', [
            'name' => 'group'
        ]);

        $this->delete('auth_item', [
            'name' => 'admin',
            'type' => 2,
        ]);

        $this->delete('auth_item', [
            'name' => 'moderator',
            'type' => 2,
        ]);

        $this->delete('auth_item', [
            'name' => 'superadmin',
            'type' => 2,
        ]);

        $this->delete('auth_item', [
            'name' => 'user',
            'type' => 2,
        ]);

        $this->delete('auth_item_child', [
            'parent' => 'superadmin',
            'child' => 'admin',
        ]);

        $this->delete('auth_item_child', [
            'parent' => 'admin',
            'child' => 'moderator',
        ]);

        $this->delete('auth_item_child', [
            'parent' => 'moderator',
            'child' => 'user',
        ]);

        $this->delete('auth_assignment', [
            'item_name' => 'superadmin',
            'user_id' => 1,
        ]);
    }
}