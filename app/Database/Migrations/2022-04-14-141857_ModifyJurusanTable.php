<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyJurusanTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('jurusan', [
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => true
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('jurusan', [
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => false
            ],
        ]);
    }
}
