<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropIdPresensiOnTablePerkuliahan extends Migration
{
    public function up()
    {
        $this->forge->dropForeignKey('perkuliahan', 'perkuliahan_id_presensi_foreign');
        $this->forge->dropColumn('perkuliahan', 'id_presensi');
    }

    public function down()
    {
        $this->forge->addColumn('perkuliahan', [
            'id_presensi'   => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true,
                'after'         => 'id'
            ]
        ]);
        $this->db->query('ALTER TABLE `dev_perkuliahan` ADD FOREIGN KEY (id_presensi) REFERENCES dev_presensi(id)');
    }
}
