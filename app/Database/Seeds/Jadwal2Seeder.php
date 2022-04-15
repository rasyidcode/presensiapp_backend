<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Jadwal2Seeder extends Seeder
{
    public function run()
    {
        $kelas = $this->db->table('kelas')
            ->get()
            ->getResultObject();
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        foreach($kelas as $kls) {
            $this->db->table('jadwal')
                ->insert([
                    'id_kelas'      => $kls->id,
                    'date'          => $today,
                    'begin_time'    => '21:00',
                    'end_time'      => '23:00',
                    'created_at'    => $now,
                    'updated_at'    => $now
                ]);
        }
    }
}
