<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaKelasSeeder extends Seeder
{
    public function run()
    {
        $kelas = $this->db->table('kelas')
            ->get()
            ->getResultObject();

        $mahasiswa = $this->db->table('mahasiswa')
            ->get()
            ->getResultObject();

        foreach($kelas as $kls) {
            foreach($mahasiswa as $mhs) {
                $this->db->table('kelas_mahasiswa')
                    ->insert([
                        'id_kelas'  => $kls->id,
                        'id_mahasiswa'    => $mhs->id
                    ]);
            }
        }
    }
}
