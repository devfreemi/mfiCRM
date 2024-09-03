<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBank extends Migration
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
            'acc_no' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '64',
            ],
            'ifsc' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'bankName' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'branch' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'bankCity' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'bankAddress' => [
                'type' => 'VARCHAR',
                'constraint' => '256',
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
        $this->forge->createTable('banks');
    }

    public function down()
    {
        //
    }
}
