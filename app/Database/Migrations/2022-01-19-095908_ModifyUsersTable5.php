<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable5 extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'username'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => false,
                'unique'        => true
            ]
        ]);
        $this->forge->modifyColumn('users', [
            'email'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => false,
                'unique'        => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'username'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
                'unique'        => false
            ]
        ]);
        $this->forge->modifyColumn('users', [
            'email'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true,
                'unique'        => false
            ]
        ]);
    }
}
