<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyJadwalTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('jadwal', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('jadwal', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => false,
            ]
        ]);
    }
}
