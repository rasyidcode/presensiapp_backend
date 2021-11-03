<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelasMhsTable extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_kelas' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_mhs' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
        ]);
        $this->forge->addForeignKey('id_kelas', 'kelas', 'id');
        $this->forge->addForeignKey('id_mhs', 'mahasiswa', 'id');
        $this->forge->createTable('kelas_mhs');
    }

    public function down()
    {
        $this->forge->dropTable('kelas_mhs');
    }
}
