<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenamePerkuliahanTableToPresensiTable extends Migration
{
    public function up()
    {
        $this->forge->renameTable('perkuliahan', 'presensi');
    }

    public function down()
    {
        //
    }
}
