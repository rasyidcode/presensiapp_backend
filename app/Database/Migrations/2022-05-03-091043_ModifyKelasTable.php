<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyKelasTable extends Migration
{
    public function up()
    {
        // $this->forge->dropForeignKey('kelas', 'id_periode');
        // $this->forge->dropColumn('kelas', 'id_periode');
        $this->forge->modifyColumn('kelas', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => true,
            ]
        ]);
    }

    public function down()
    {
        // $this->forge->addColumn('kelas', [
        //     'id_periode' => [
        //         'type' => 'int',
        //         'constraint' => 9,
        //         'null' => true
        //     ],
        // ]);
        // $this->db->query('ALTER TABLE `dev_kelas` ADD FOREIGN KEY (id_periode) REFERENCES dev_periode(id)');
        $this->forge->modifyColumn('kelas', [
            'deleted_at'    => [
                'type'  => 'datetime',
                'null'  => false,
            ]
        ]);
    }
}
