<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // $this->db->disableForeignKeyChecks();
        
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
                'constraint'    => 255,
                'null'          => true
            ],
            'level'  => [
                'type'          => 'enum("admin", "dosen", "mhs")',
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
        $this->forge->createTable('users');

        // $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        // $this->db->disableForeignKeyChecks();

        $this->forge->dropTable('users');

        // $this->db->enableForeignKeyChecks();
    }
}
