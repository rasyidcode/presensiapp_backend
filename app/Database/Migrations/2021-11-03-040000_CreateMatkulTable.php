<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMatkulTable extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'kode_matkul'   => [
                'type'          => 'char',
                'constraint'    => 5,
                'null'          => true
            ],
            'nama_matkul'  => [
                'type'          => 'varchar',
                'constraint'    => 255,
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => null
            ],
        ]);
        $this->forge->createTable('matkul');
    }

    public function down()
    {
        $this->forge->dropTable('matkul');
    }
}
