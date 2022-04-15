<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPerkuliahanTable2 extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('perkuliahan', [
            'id_jadwal' => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => false
            ],
        ]);
        $this->db->query('ALTER TABLE `dev_perkuliahan` ADD FOREIGN KEY (id_jadwal) REFERENCES dev_jadwal(id)');
    }

    public function down()
    {
        $this->forge->modifyColumn('perkuliahan', [
            'id_jadwal' => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true
            ],
        ]);
        $this->forge->dropForeignKey('perkuliahan', 'id_jadwal');
    }
}
