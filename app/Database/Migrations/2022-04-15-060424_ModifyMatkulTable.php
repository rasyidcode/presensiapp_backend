<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyMatkulTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('matkul', [
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => true
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('matkul', [
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => false
            ],
        ]);
    }
}
