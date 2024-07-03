<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBranch extends Migration
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
            'b_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'location' => [
                'type' => 'VARCHAR',

                'constraint' => '64',
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'unique' => true,
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
        $this->forge->createTable('branches');
    }

    public function down()
    {
        //
        $this->forge->dropTable('employees');
    }
}
