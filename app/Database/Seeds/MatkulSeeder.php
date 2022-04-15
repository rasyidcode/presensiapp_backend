<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MatkulSeeder extends Seeder
{
    public function run()
    {
        $jurusan = [
            'PP'    => 'Pengenalan Pemrograman',
            'TIK'   => 'Teknologi Informasi dan Komunikasi',
            'PD'    => 'Pemrograman Dasar',
            'PTL'   => 'Pemrograman Tingkat Lanjut',
            'PK'    => 'Pemrograman Kreatif',
        ];

        $data = [];
        foreach($jurusan as $key => $val) {
            $now = date('Y-m-d H:i:s');
            $newData['kode_matkul'] = $key;
            $newData['nama_matkul'] = $val;
            $newData['created_at'] = $now;
            $newData['updated_at'] = $now;
            $data[] = $newData;
        }

        $this->db->table('matkul')
            ->insertBatch($data);
    }
}
