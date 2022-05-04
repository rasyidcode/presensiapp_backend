<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            [
                'id_dosen'  => 1,
                'id_matkul' => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 1,
                'id_matkul' => 2,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 2,
                'id_matkul' => 3,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 2,
                'id_matkul' => 4,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 3,
                'id_matkul' => 5,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('kelas')
            ->insertBatch($data);
    }
}
