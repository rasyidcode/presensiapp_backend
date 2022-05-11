<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPresensiTable extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('presensi', 'presensi_id_jadwal_foreign');
        $this->forge->dropColumn('presensi', 'id_jadwal');
    }

    public function down()
    {
        $this->forge->addColumn('presensi', [
            'id_jadwal' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
        ]);
        $this->db->query('ALTER TABLE `presensi` ADD FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal`(`id`)');
    }
}
