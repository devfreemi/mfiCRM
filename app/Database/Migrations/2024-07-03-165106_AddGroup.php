<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGroup extends Migration
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
            'g_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'g_id' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '64',
            ],
            'branch' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'location' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '255',
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => '7',
            ],
            'group_type' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'group_leader' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => true
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
        $this->forge->createTable('groups');
    }

    public function down()
    {
        //
        $this->forge->dropTable('groups');
    }
}
