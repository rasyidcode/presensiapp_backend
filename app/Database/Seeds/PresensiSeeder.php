<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PresensiSeeder extends Seeder
{
    public function run()
    {
        $kelasId = 7;
        $dosenQrId = 1;
        $mahasiswaList = $this->db->table('kelas_mahasiswa')
            ->select('id_mahasiswa')
            ->where('id_kelas', $kelasId)
            ->get()
            ->getResultObject();
        
        foreach($mahasiswaList as $mahasiswaItem) {
            $this->db->table('presensi')
                ->insert([
                    'id_dosen_qrcode'   => $dosenQrId,
                    'id_mahasiswa'      => $mahasiswaItem->id_mahasiswa,
                    'status_presensi'   => 1
                ]);
        }
    }
}
