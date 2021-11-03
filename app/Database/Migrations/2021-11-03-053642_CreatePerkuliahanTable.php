<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerkuliahanTable extends Migration
{
    public function up()
    {
        // (id, id_jadwal, id_mhs, status_presensi, created_at)
        $this->forge->addField('id');
        $this->forge->addField([
            'id_presensi' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_mhs' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'status_presensi' => [ // 0 => tidak hadir, 1 => hadir, 2 => izin, 3 => sakit
                'type' => 'tinyint',
                'constraint' => 1,
                'default' => 0,
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
        ]);
        $this->forge->addForeignKey('id_presensi', 'presensi', 'id');
        $this->forge->addForeignKey('id_mhs', 'mahasiswa', 'id');
        $this->forge->createTable('perkuliahan');
    }

    public function down()
    {
        $this->forge->dropTable('perkuliahan');
    }
}
