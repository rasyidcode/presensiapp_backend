<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'  => 'admin',
                'password'  => password_hash('12345', PASSWORD_BCRYPT),
                'email'     => 'admin@example.com',
                'level'     => 'admin',
            ],
            [
                'username'  => 'dosen',
                'password'  => password_hash('12345', PASSWORD_BCRYPT),
                'email'     => 'dosen@example.com',
                'level'     => 'dosen',
            ],
            [
                'username'  => 'mahasiswa',
                'password'  => password_hash('12345', PASSWORD_BCRYPT),
                'email'     => 'mahasiswa@example.com',
                'level'     => 'mahasiswa',
            ],
        ];

        $this->db->table('users')
            ->insertBatch($data);
    }
}
