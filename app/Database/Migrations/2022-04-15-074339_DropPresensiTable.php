<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropPresensiTable extends Migration
{
    public function up()
    {
        $this->forge->dropTable('presensi');
    }

    public function down()
    {
        //
    }
}
