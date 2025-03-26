<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EligibiltyRun extends Migration
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
            'first_date' => [
                'type' => 'DATE',

            ],
            'second_date' => [
                'type' => 'DATE',

            ],
            'loan_amount' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
            ],
            'roi' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
            ],
            'tenure' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
            ],
            'score' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
            ],
            'cibil' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
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
        $this->forge->createTable('initial_eli_run');
    }

    public function down()
    {
        //
        $this->forge->dropTable('initial_eli_run');
    }
}
