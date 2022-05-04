<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        $jurusan = [
            'TI'    => 'Teknik Informatika',
            'MI'    => 'Manajemen Informatika',
            'EK'    => 'Ekonomi',
            'HU'    => 'Hukum',
            'TIN'    => 'Teknik Industri',
        ];

        $data = [];
        foreach($jurusan as $key => $val) {
            $now = date('Y-m-d H:i:s');
            $newData['kode'] = $key;
            $newData['nama'] = $val;
            $newData['created_at'] = $now;
            $newData['updated_at'] = $now;
            $data[] = $newData;
        }

        $this->db->table('jurusan')
            ->insertBatch($data);
    }
}
