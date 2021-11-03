<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJadwalTable extends Migration
{
    public function up()
    {
        // (id, id_kelas, date, begin_time, end_time, created_at, updated_at, deleted_at)
        $this->forge->addField('id');
        $this->forge->addField([
            'id_kelas' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'date' => [
                'type' => 'date',
                'null' => true
            ],
            'begin_time' => [
                'type' => 'time',
                'null' => true
            ],
            'end_time' => [
                'type' => 'time',
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => null
            ],
        ]);
        $this->forge->addForeignKey('id_kelas', 'kelas', 'id');
        $this->forge->createTable('jadwal');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal');
    }
}