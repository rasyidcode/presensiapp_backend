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
                'id_dosen'  => 6,
                'id_matkul' => 6,
                'id_periode'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 6,
                'id_matkul' => 9,
                'id_periode'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 7,
                'id_matkul' => 7,
                'id_periode'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 7,
                'id_matkul' => 8,
                'id_periode'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id_dosen'  => 9,
                'id_matkul' => 10,
                'id_periode'    => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $this->db->table('kelas')
            ->insertBatch($data);
    }
}
