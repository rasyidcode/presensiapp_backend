<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPeriodeTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('periode', [
            'semester'  => [
                'type'  => 'tinyint',
                'constraint'    => 1,
                'null'  => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('periode', [
            'semester' => [
                'type'  => 'varchar',
                'constraint' => 25,
                'null' => true
            ],
        ]);
    }
}
