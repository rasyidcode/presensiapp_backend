<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMahasiswaTable extends Migration
{
    public function up()
    {
        // $this->db->disableForeignKeyChecks();

        $this->forge->addField('id');
        $this->forge->addField([
            'nim'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true
            ],
            'nama_lengkap'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true
            ],
            'id_user'  => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true
            ],
            'id_jurusan'  => [
                'type'          => 'int',
                'constraint'    => 9,
                'null'          => true
            ],
            'tahun_masuk'  => [
                'type'          => 'smallint',
                'constraint'    => 4,
                'null'          => true
            ],
            'jenis_kelamin'  => [
                'type'          => 'char',
                'constraint'    => 1,
                'default'       => 'L',
                'null'          => true
            ],
            'alamat'  => [
                'type'          => 'varchar',
                'constraint'    => 255,
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => null
            ],
        ]);

        $this->forge->addForeignKey('id_jurusan', 'jurusan', 'id');
        $this->forge->addForeignKey('id_user', 'users', 'id');

        $this->forge->createTable('mahasiswa');
        

        // $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        // $this->db->disableForeignKeyChecks();

        $this->forge->dropTable('mahasiswa');

        // $this->db->enableForeignKeyChecks();
    }
}
