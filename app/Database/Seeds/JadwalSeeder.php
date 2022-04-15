<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $kelas = $this->db->table('kelas')
            ->get()
            ->getResultObject();
        $now = date('Y-m-d H:i:s');
        foreach($kelas as $kls) {
            $startingDate = '';

            if ($kls->id == 1) {
                $startingDate = date('Y-m-d', strtotime('2022-04-18'));
            } else if ($kls->id == 2) {
                $startingDate = date('Y-m-d', strtotime('2022-04-19'));
            } else if ($kls->id == 3) {
                $startingDate = date('Y-m-d', strtotime('2022-04-20'));
            } else if ($kls->id == 4) {
                $startingDate = date('Y-m-d', strtotime('2022-04-21'));
            } else if ($kls->id == 5) {
                $startingDate = date('Y-m-d', strtotime('2022-04-22'));
            }

            for ($i = 0; $i < 24; $i++) {
                $startingDate = date('Y-m-d', strtotime($startingDate . '+7 day'));
                $this->db->table('jadwal')
                    ->insert([
                        'id_kelas'      => $kls->id,
                        'date'          => $startingDate,
                        'begin_time'    => '07:00',
                        'end_time'      => '11:00',
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ]);
            }
        }
    }
}
