<?php

use yii\db\Schema;
use yii\db\Migration;

class m160224_162315_upgrade_bids_files extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('bids_files', [
            'id' => Schema::TYPE_PK,
            'bid_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'filename' => Schema::TYPE_STRING . '(100) DEFAULT NULL'
        ]);

        $this->dropColumn('bids', 'filename');

        $this->addForeignKey(
            'fk_bids_files_bid_id_bids_id',
            'bids_files', 'bid_id',
            'bids', 'id'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_bids_files_bid_id_bids_id', 'bids_files');
        
        $this->dropTable('bids_files');

        $this->addColumn('bids', 'filename', Schema::TYPE_STRING . '(50) DEFAULT NULL');
    }
}
