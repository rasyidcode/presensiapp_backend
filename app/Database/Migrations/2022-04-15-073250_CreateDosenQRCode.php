<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDosenQRCode extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_dosen' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'qr_secret' => [
                'type'  => 'varchar',
                'constraint'    => 255,
                'null'  => true,
            ],
            'created_at timestamp default current_timestamp() null',
        ]);
        $this->forge->addForeignKey('id_dosen', 'dosen', 'id');
        $this->forge->createTable('dosen_qrcode');
    }

    public function down()
    {
        $this->forge->dropTable('dosen_qrcode');
    }
}
