<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Agentattendences extends Migration
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
            'agent_id' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'date' => [
                'type' => 'DATE',

            ],
            'sign_in_time' => [
                'type' => 'time',

            ],
            'sign_out_time' => [
                'type' => 'time',

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
        $this->forge->createTable('agentattendences');
    }

    public function down()
    {
        //
        $this->forge->dropTable('agentattendences');
    }
}
