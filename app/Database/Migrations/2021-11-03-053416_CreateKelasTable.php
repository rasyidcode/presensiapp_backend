<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelasTable extends Migration
{
    public function up()
    {
        //(id, id_dosen, id_matkul, id_periode, created_at, updated_at, deleted_at)
        $this->forge->addField('id');
        $this->forge->addField([
            'id_dosen' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_matkul' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_periode' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => null
            ],
        ]);

        $this->forge->addForeignKey('id_periode', 'periode', 'id');
        $this->forge->addForeignKey('id_matkul', 'matkul', 'id');
        $this->forge->addForeignKey('id_dosen', 'dosen', 'id');
        
        $this->forge->createTable('kelas');
    }

    public function down()
    {
        $this->forge->dropTable('kelas');
    }
}
