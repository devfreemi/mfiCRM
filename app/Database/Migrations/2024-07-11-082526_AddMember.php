<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMember extends Migration
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
                'unique' => true,
                'constraint' => '64',
            ],
            'groupName' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'groupId' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '12',
            ],
            'pan' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '12',
            ],
            'adhar' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '12',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => '7',
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'marital' => [
                'type' => 'VARCHAR',
                'constraint' => '64',

            ],
            'occupation' => [
                'type' => 'VARCHAR',
                'constraint' => '64',

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
        $this->forge->createTable('members');
    }

    public function down()
    {
        //
        $this->forge->dropTable('members');
    }
}
