<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGeoTag extends Migration
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
            'agent_id' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'latitude' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'longitude' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'reference' => [
                'type' => 'VARCHAR',
                'constraint' => '1064',
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
        $this->forge->createTable('geotags');
    }

    public function down()
    {
        //
        $this->forge->dropTable('geotags');
    }
}
