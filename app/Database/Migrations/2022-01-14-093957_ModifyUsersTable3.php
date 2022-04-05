<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUsersTable3 extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => true    
            ]            
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => false    
            ]            
        ]);
    }
}
