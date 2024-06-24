<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmployee extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '64',
            ],
            'employeeID' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '64',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '255',
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
        $this->forge->createTable('employees');
    }

    public function down()
    {
        //
        $this->forge->dropTable('employees');
    }
}
