<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyBlacklistTokenTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('blacklist_token', [
            'token' => [
                'type'  => 'text',
                'null'  => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('blacklist_token', [
            'token' => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ]
        ]);
    }
}
