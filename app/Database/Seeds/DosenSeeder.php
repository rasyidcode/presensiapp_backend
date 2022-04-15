<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $dosen = [
            [
                'nip'           => '55.12345.99',
                'nama_lengkap'  => 'Joe Biden',
                'email'         => 'joe@mama.com',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Queens, US'
            ],
            [
                'nip'           => '55.12345.98',
                'nama_lengkap'  => 'Barrack Obama',
                'email'         => 'barrack@obama.com',
                'jenis_kelamin' => 'L',
                'alamat'        => 'West Coast, US'
            ],
            [
                'nip'           => '55.12345.97',
                'nama_lengkap'  => 'Hillary Clinton',
                'email'         => 'hillary@grandma-clinton.com',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Washington DC, US'
            ],
            [
                'nip'           => '69.12345.00',
                'nama_lengkap'  => 'Vladimir Putin',
                'email'         => 'vlad@gigachad.putin',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Moscow, Rusia'
            ],
            [
                'nip'           => '69.12345.01',
                'nama_lengkap'  => 'Zelensky',
                'email'         => 'idiot@zelensky.com',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Kiev, Ukrine'
            ],
        ];

        $userdata = [];
        $now = date('Y-m-d H:i:s');
        foreach($dosen as $dsn) {
            $newUser['username']    = $dsn['nip'];
            $newUser['password']    = password_hash('12345', PASSWORD_BCRYPT);
            $newUser['email']       = $dsn['email'];
            $newUser['level']       = 'dosen';
            $newUser['created_at']  = $now;
            $newUser['updated_at']  = $now;

            $newUser['extra']       = [
                $dsn['nama_lengkap'],
                $dsn['jenis_kelamin'],
                $dsn['alamat'],
            ];

            $userdata[] = $newUser;
        }

        foreach($userdata as $user) {
            $extra = array_pop($user);
            $this->db->table('users')
                ->insert($user);
            $lastId = $this->db->insertID();

            $now = date('Y-m-d H:i:s');

            $newDosen['nip']            = $user['username'];
            $newDosen['nama_lengkap']   = $extra[0];
            $newDosen['id_user']        = $lastId;
            $newDosen['tahun_masuk']    = '2022';
            $newDosen['jenis_kelamin']  = $extra[1];
            $newDosen['alamat']         = $extra[2];
            $newDosen['created_at']     = $now;
            $newDosen['updated_at']     = $now;
            $this->db->table('dosen')
                ->insert($newDosen);
        }
    }
}
