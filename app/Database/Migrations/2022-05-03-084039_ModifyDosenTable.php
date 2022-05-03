<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyDosenTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('dosen', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('dosen', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => false,
            ]
        ]);
    }
}
