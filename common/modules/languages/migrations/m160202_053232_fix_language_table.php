<?php

use yii\db\Migration;

class m160202_053232_fix_language_table extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->update('languages', ['codeFull' => 'en-EN'], ['code' => 'en']);
        $this->update('languages', ['codeFull' => 'ru-RU'], ['code' => 'ru']);

        $this->renameColumn('languages', 'codeFull', 'local');
        $this->renameColumn('languages', 'code', 'url');

        $this->addColumn('languages', 'default', 'smallint(6) NOT NULL DEFAULT 0');

        $this->update('languages', ['default' => 1], ['url' => 'ru']);
    }

    public function safeDown()
    {
        $this->renameColumn('languages', 'local', 'codeFull');
        $this->renameColumn('languages', 'url', 'code');

        $this->update('languages', ['codeFull' => 'eng'], ['code' => 'en']);
        $this->update('languages', ['codeFull' => 'rus'], ['code' => 'ru']);

        $this->dropColumn('languages', 'default');
    }
}
