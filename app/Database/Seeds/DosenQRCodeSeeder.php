<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DosenQRCodeSeeder extends Seeder
{
    public function run()
    {
        $jadwalId = 128;
        $dosenId = 4;
        $qrsecret = uniqid();
        $this->db->table('dosen_qrcode')
            ->insert([
                'id_dosen'  => $dosenId,
                'id_jadwal' => $jadwalId,
                'qr_secret' => $qrsecret
            ]);
    }
}
