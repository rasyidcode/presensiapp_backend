<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run()
    {
        /**
         * semester: 
         * 1 => ganjil
         * 2 => genap
         * 3 => tambahan
         */
        $now = date('Y-m-d H:i:s');
        $this->db->table('periode')
            ->insertBatch([
                [
                    'semester'      => 1,
                    'year'          => '2021',
                    'start_at'      => '2021-03-15',
                    'end_at'        => '2021-09-15',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ],
                [
                    'semester'      => 2,
                    'year'          => '2021',
                    'start_at'      => '2021-09-15',
                    'end_at'        => '2022-03-15',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ],
                [
                    'semester'      => 1,
                    'year'          => '2022',
                    'start_at'      => '2022-03-15',
                    'end_at'        => '2022-09-15',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ],
                [
                    'semester'      => 2,
                    'year'          => '2022',
                    'start_at'      => '2022-09-15',
                    'end_at'        => '2023-03-15',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ],
                [
                    'semester'      => 1,
                    'year'          => '2023',
                    'start_at'      => '2023-03-15',
                    'end_at'        => '2023-09-15',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ],
                [
                    'semester'      => 2,
                    'year'          => '2023',
                    'start_at'      => '2023-09-15',
                    'end_at'        => '2024-03-15',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ],
            ]);
    }
}
