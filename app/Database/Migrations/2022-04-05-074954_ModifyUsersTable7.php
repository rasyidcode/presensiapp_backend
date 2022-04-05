<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable7 extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'token'    => [
                'type'          => 'text',
                'null'          => true
            ]            
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'token' => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true,
                'after'         => 'level'
            ]     
        ]);
    }
}
