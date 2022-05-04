<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        // user table
        $this->usersTableUp();

        // jurusan table
        $this->jurusanTableUp();

        // matkul table
        $this->matkulTableUp();

        // mahasiswa table
        $this->mahasiswaTableUp();

        // dosen table
        $this->dosenTableUp();

        // blacklist_token table
        $this->blacklistTokenTableUp();

        // kelas table
        $this->kelasTableUp();

        // kelas_mahasiswa table
        $this->kelasMahasiswaTableUp();

        // dosen_qrcode table
        $this->dosenQrcodeTableUp();

        // jadwal table
        $this->jadwalTableUp();

        // presensi table
        $this->presensiTableUp();

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();

        // user table
        $this->usersTableDown();

        // jurusan table
        $this->jurusanTableDown();

        // matkul table
        $this->matkulTableDown();

        // mahasiswa table
        $this->mahasiswaTableDown();

        // dosen table
        $this->dosenTableDown();

        // blacklist_token table
        $this->blacklistTokenTableDown();

        // kelas table
        $this->kelasTableDown();

        // kelas_mahasiswa table
        $this->kelasMahasiswaTableDown();

        // dosen_qrcode table
        $this->dosenQrcodeTableDown();

        // jadwal table
        $this->jadwalTableDown();

        // presensi table
        $this->presensiTableDown();

        $this->db->enableForeignKeyChecks();
    }

    private function usersTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'username'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true
            ],
            'password'  => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],
            'email'  => [
                'type'          => 'varchar',
                'constraint'    => 50,
                'null'          => true
            ],
            'level'  => [
                'type'          => 'enum("admin", "dosen", "mahasiswa")',
                'null'          => true
            ],
            'token' => [
                'type'          => 'text',
                'null'          => true,
            ],
            'last_login'    => [
                'type'          => 'datetime',
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => true
            ],
        ]);
        $this->forge->createTable('users');
    }

    private function usersTableDown()
    {
        $this->forge->dropTable('users');
    }

    private function jurusanTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'kode'  => [
                'type'          => 'char',
                'constraint'    => 5,
                'null'          => true
            ],
            'nama'  => [
                'type'          => 'varchar',
                'constraint'    => 255,
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
        ]);
        $this->forge->createTable('jurusan');
    }

    private function jurusanTableDown()
    {
        $this->forge->dropTable('jurusan');
    }

    private function matkulTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'kode'  => [
                'type'          => 'char',
                'constraint'    => 5,
                'null'          => true
            ],
            'nama'  => [
                'type'          => 'varchar',
                'constraint'    => 255,
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
        ]);
        $this->forge->createTable('matkul');
    }

    private function matkulTableDown()
    {
        $this->forge->dropTable('matkul');
    }

    private function mahasiswaTableUp()
    {
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
                'type'          => 'enum("L", "P")',
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
                'null'          => true
            ],
        ]);

        $this->forge->addForeignKey('id_jurusan', 'jurusan', 'id');
        $this->forge->addForeignKey('id_user', 'users', 'id');

        $this->forge->createTable('mahasiswa');
    }

    private function mahasiswaTableDown()
    {
        $this->forge->dropTable('mahasiswa');
    }

    private function dosenTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'nip'  => [
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
            'tahun_masuk'  => [
                'type'          => 'smallint',
                'constraint'    => 4,
                'null'          => true
            ],
            'jenis_kelamin'  => [
                'type'          => 'enum("L", "P")',
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
                'null'          => true
            ],
        ]);

        $this->forge->addForeignKey('id_user', 'users', 'id');

        $this->forge->createTable('dosen');
    }

    private function dosenTableDown()
    {
        $this->forge->dropTable('dosen');
    }

    private function blacklistTokenTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'token'  => [
                'type'          => 'varchar',
                'constraint'    => 100,
                'null'          => true
            ],
            'created_at timestamp default current_timestamp() null',
        ]);
        $this->forge->createTable('blacklist_token');
    }

    private function blacklistTokenTableDown()
    {
        $this->forge->dropTable('blacklist_token');
    }

    private function kelasTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_dosen' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_matkul' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => true
            ],
        ]);

        $this->forge->addForeignKey('id_dosen', 'dosen', 'id');
        $this->forge->addForeignKey('id_matkul', 'matkul', 'id');
        
        $this->forge->createTable('kelas');
    }

    private function kelasTableDown()
    {
        $this->forge->dropTable('kelas');
    }

    private function kelasMahasiswaTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_kelas' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_mahasiswa' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
        ]);
        $this->forge->addForeignKey('id_kelas', 'kelas', 'id');
        $this->forge->addForeignKey('id_mahasiswa', 'mahasiswa', 'id');
        $this->forge->createTable('kelas_mahasiswa');
    }

    private function kelasMahasiswaTableDown()
    {
        $this->forge->dropTable('kelas_mahasiswa');
    }

    private function dosenQrcodeTableUp()
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

    private function dosenQrcodeTableDown()
    {
        $this->forge->dropTable('dosen_qrcode');
    }

    private function jadwalTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_kelas' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'date' => [
                'type' => 'date',
                'null' => true
            ],
            'begin_time' => [
                'type' => 'time',
                'null' => true
            ],
            'end_time' => [
                'type' => 'time',
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
            'updated_at timestamp default current_timestamp() null',
            'deleted_at'    => [
                'type'          => 'datetime',
                'null'          => true
            ],
        ]);
        $this->forge->addForeignKey('id_kelas', 'kelas', 'id');
        $this->forge->createTable('jadwal');    
    }

    private function jadwalTableDown()
    {
        $this->forge->dropTable('jadwal');
    }

    private function presensiTableUp()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'id_dosen_qrcode' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_jadwal' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'id_mahasiswa' => [
                'type' => 'int',
                'constraint' => 9,
                'null' => true
            ],
            'status_presensi' => [ // 0 => tidak hadir, 1 => hadir, 2 => terlambat
                'type' => 'tinyint',
                'constraint' => 1,
                'null' => true
            ],
            'created_at timestamp default current_timestamp() null',
        ]);
        $this->forge->addForeignKey('id_dosen_qrcode', 'dosen_qrcode', 'id');
        $this->forge->addForeignKey('id_jadwal', 'jadwal', 'id');
        $this->forge->addForeignKey('id_mahasiswa', 'mahasiswa', 'id');

        $this->forge->createTable('presensi');
    }

    private function presensiTableDown()
    {
        $this->forge->dropTable('presensi');
    }
}
