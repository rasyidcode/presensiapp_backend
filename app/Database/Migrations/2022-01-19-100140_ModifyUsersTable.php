<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'password'  => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => false
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'password'  => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],
        ]);
    }
}
