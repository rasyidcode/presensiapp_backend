<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlacklistTokenTable extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'token'  => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
        ]);
        $this->forge->createTable('blacklist_token');
    }

    public function down()
    {
        $this->forge->dropTable('blacklist_token');
    }
}
