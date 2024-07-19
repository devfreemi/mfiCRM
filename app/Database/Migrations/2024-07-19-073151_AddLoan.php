<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLoan extends Migration
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
            'groupId' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'member_id' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '64',
            ],
            'loan_amount' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'loan_type' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'loan_status' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'employee_id' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
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
        $this->forge->createTable('loans');
    }

    public function down()
    {
        //
        $this->forge->dropTable('loans');
    }
}
