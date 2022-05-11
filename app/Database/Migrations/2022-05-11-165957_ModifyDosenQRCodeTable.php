<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyDosenQRCodeTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dosen_qrcode', [
            'id_jadwal' => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true,
                'after'         => 'id_dosen'
            ],
        ]);
        $this->db->query('ALTER TABLE `dosen_qrcode` ADD FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal`(`id`)');
    }

    public function down()
    {
        $this->forge->dropForeignKey('dosen_qrcode', 'dosen_qrcode_id_jadwal_foreign');
        $this->forge->dropColumn('dosen_qrcode', 'id_jadwal');
    }
}
