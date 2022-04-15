<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = [
            [
                'nim'           => '123456789',
                'nama_lengkap'  => 'Joko Widodo',
                'email'         => 'joko@3periode.com',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Solo, Indonesia'
            ],
            [
                'nim'           => '123456788',
                'nama_lengkap'  => 'Megawati Soekarno Putri',
                'email'         => 'mega@nekopoi.site',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Bogor, Indonesia'
            ],
            [
                'nim'           => '123456787',
                'nama_lengkap'  => 'Amin Rais',
                'email'         => 'amin@ulama.com',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Makassar, Indonesia'
            ],
            [
                'nim'           => '123456786',
                'nama_lengkap'  => 'Prabowo Subianto',
                'email'         => 'prabowo@gerindra.com',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Yogyakarta, Indonesia'
            ],
            [
                'nim'           => '123456785',
                'nama_lengkap'  => 'Luhut',
                'email'         => 'luhut@bumame.com',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Medan, Indonesia'
            ],
            [
                'nim'           => '123456784',
                'nama_lengkap'  => 'Puan Maharani',
                'email'         => 'puan@nekopoi.xyz',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Tanggerang, Indonesia'
            ],
        ];

        $userdata = [];
        $now = date('Y-m-d H:i:s');
        foreach($mahasiswa as $mhs) {
            $newUser['username']    = $mhs['nim'];
            $newUser['password']    = password_hash('12345', PASSWORD_BCRYPT);
            $newUser['email']       = $mhs['email'];
            $newUser['level']       = 'mahasiswa';
            $newUser['created_at']  = $now;
            $newUser['updated_at']  = $now;

            $newUser['extra']       = [
                $mhs['nama_lengkap'],
                $mhs['jenis_kelamin'],
                $mhs['alamat'],
            ];

            $userdata[] = $newUser;
        }

        foreach($userdata as $user) {
            $extra = array_pop($user);
            $this->db->table('users')
                ->insert($user);
            $lastId = $this->db->insertID();

            $now = date('Y-m-d H:i:s');

            $newMahasiswa['nim']            = $user['username'];
            $newMahasiswa['nama_lengkap']   = $extra[0];
            $newMahasiswa['id_user']        = $lastId;
            $newMahasiswa['id_jurusan']     = $this->getRandomJurusanID();
            $newMahasiswa['tahun_masuk']    = '2022';
            $newMahasiswa['jenis_kelamin']  = $extra[1];
            $newMahasiswa['alamat']         = $extra[2];
            $newMahasiswa['created_at']     = $now;
            $newMahasiswa['updated_at']     = $now;
            $this->db->table('mahasiswa')
                ->insert($newMahasiswa);
        }
    }

    private function getMahasiswa(string $nim, array $data) : array
    {
        $idx = array_search($nim, $data);
        return $data[$idx];
    }

    private function getNamaLengkap(string $nim, array $data)
    {
        return $data[array_search($nim, $data)]['nama_lengkap'];
    }

    private function getRandomJurusanID() : int
    {
        $ids = $this->db->table('jurusan')
            ->select('id')
            ->get()
            ->getResultArray();

        return $ids[rand(0, count($ids) - 1)]['id'];
    }
}
