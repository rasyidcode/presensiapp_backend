<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPerkuliahanTable3 extends Migration
{
    public function up()
    {
        $this->forge->addColumn('perkuliahan', [
            'id_dosen_qrcode'   => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true,
                'after'         => 'id'
            ]
        ]);
        $this->db->query('ALTER TABLE `dev_perkuliahan` ADD FOREIGN KEY (id_dosen_qrcode) REFERENCES dev_dosen_qrcode(id)');
    }

    public function down()
    {
        $this->forge->dropForeignKey('perkuliahan', 'perkuliahan_id_dosen_qrcode_foreign');
        $this->forge->dropColumn('perkuliahan', 'id_dosen_qrcode');
    }
}
