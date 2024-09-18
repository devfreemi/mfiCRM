<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMasterDeposite extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 255,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'agent' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'deposited_amount' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'deposited_date' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'bank_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'bank_account_number' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'receipt_url' => [
                'type' => 'VARCHAR',
                'constraint' => '2048',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('bank_deposites_master');
    }

    public function down()
    {
        //
        $this->forge->dropTable('bank_deposites_master');
    }
}
