<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RetailerDocumentsMigration extends Migration
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
                'unique' => true,
            ],
            'document_path' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'default' => null,
                // 'constraint' => '2064',
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
        $this->forge->createTable('retailerdocuments');
    }

    public function down()
    {
        //
        //
        $this->forge->dropTable('retailerdocuments');
    }
}
