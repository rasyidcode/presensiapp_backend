<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'token' => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true,
                'after'         => 'level'
            ],
            'last_login'    => [
                'type'          => 'varchar',
                ''
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'token');
    }
}
