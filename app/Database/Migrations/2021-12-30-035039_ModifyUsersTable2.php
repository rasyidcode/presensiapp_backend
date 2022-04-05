<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable2 extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('users', 'tahun_masuk');
    }

    public function down()
    {
        $this->forge->addColumn('users', [
            'tahun_masuk'  => [
                'type'          => 'smallint',
                'constraint'    => 4,
                'null'          => true
            ]
        ]);
    }
}
