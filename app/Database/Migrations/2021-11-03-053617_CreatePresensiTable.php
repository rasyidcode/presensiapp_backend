<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePresensiTable extends Migration
{
    public function up()
    {
        // (id, id_jadwal, secret, expired_at, created_at)
        $this->forge->addField('id');
        $this->forge->addField([
            'id_jadwal' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'secret' => [
                'type' => 'varchar',
                'constraint' => 69,
                'null' => true
            ],
            'expired_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
        ]);
        $this->forge->addForeignKey('id_jadwal', 'jadwal', 'id');
        $this->forge->createTable('presensi');
    }

    public function down()
    {
        $this->forge->dropTable('presensi');
    }
}
