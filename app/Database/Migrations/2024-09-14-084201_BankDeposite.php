<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BankDeposite extends Migration
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
            'member_id' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'group_id' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'collected_amount' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'collection_date' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'agent' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'created_at' => [
                'type' => 'DATE',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('bank_deposites');
    }

    public function down()
    {
        //
        $this->forge->dropTable('bank_deposites');
    }
}
