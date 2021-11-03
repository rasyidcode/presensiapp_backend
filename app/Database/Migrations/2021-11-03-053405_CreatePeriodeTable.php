<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeriodeTable extends Migration
{
    public function up()
    {
        // id, semester, year, start_at, end_at, created_at, updated_at, deleted_at)
        $this->forge->addField('id');
        $this->forge->addField([
            'semester' => [
                'type'  => 'varchar',
                'constraint' => 25,
                'null' => true
            ],
            'year' => [
                'type' => 'smallint',
                'constraint' => 4,
                'null' => true
            ],
            'start_at' => [
                'type' => 'date',
                'null' => true,
            ],
            'end_at' => [
                'type' => 'date',
                'null' => true,
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => null
            ],
        ]);
        $this->forge->createTable('periode');
    }

    public function down()
    {
        $this->forge->dropTable('periode');
    }
}
