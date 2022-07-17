<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropDosenIDAtDosenQRCode extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('dosen_qrcode', 'dosen_qrcode_id_dosen_foreign');
        $this->forge->dropColumn('dosen_qrcode', 'id_dosen');
    }

    public function down()
    {
        $this->forge->addColumn('dosen_qrcode', [
            'id_dosen'  => [
                'type'          => 'INT',
                'constraint'    => 9,
                'null'          => true
            ]
        ]);
        $this->db->query('ALTER TABLE `dosen_qrcode` ADD FOREIGN KEY (`id_dosen`) REFERENCES `dosen`(`id`)');
    }
}
