<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPanAndGst extends Migration
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
            'pan' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
            ],
            'gst' => [
                'type' => 'LONGTEXT',
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
        $this->forge->createTable('pan_gst_master');
    }

    public function down()
    {
        //
        $this->forge->dropTable('pan_gst_master');
    }
}
