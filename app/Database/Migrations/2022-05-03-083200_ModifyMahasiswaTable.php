<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyMahasiswaTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('mahasiswa', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('mahasiswa', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => false,
            ]
        ]);
    }
}
