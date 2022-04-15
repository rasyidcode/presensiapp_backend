<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPerkuliahanTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('perkuliahan', [
            'id_jadwal' => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true,
                'after'         => 'id'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('perkuliahan', 'id_jadwal');
    }
}
