<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable8 extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'level'    => [
                'type'          => 'enum("admin", "dosen", "mahasiswa")',
                'null'          => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'level'    => [
                'type'          => 'enum("admin", "dosen", "mhs")',
                'null'          => true
            ]
        ]);
    }
}
