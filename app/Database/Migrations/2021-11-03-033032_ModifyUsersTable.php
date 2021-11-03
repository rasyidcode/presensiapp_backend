<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        // $this->forge->dropColumn('users', 'tahun_masuk');
        $this->forge->dropColumn('users', 'jenis_kelamin');
        $this->forge->dropColumn('users', 'alamat');

        $this->forge->addColumn('users', [
            'last_login'    => [
                'type'  => 'datetime',
                'null'  => true
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
