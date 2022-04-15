<?php

namespace Modules\Api\Jadwal\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jadwal today by id_mhs
     * 
     * @param int $id_mhs
     * 
     * @return return|null
     */
    public function getJadwal(int $id_mhs)
    {
        $jadwal = $this->builder('jadwal');

        $jadwal->select('
            dosen.nip as nip_dosen,
            dosen.nama_lengkap as nama_dosen,

            matkul.kode_matkul,
            matkul.nama_matkul,

            jadwal.date,
            jadwal.begin_time,
            jadwal.end_time
        ');

        $jadwal->join('kelas', 'jadwal.id_kelas = kelas.id', 'left');
        $jadwal->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');
        $jadwal->join('kelas_mhs', 'kelas.id = kelas_mhs.id_kelas', 'left');

        $jadwal->where('jadwal.date', date('Y-m-d'));
        $jadwal->where('jadwal.deleted_at', null);
        $jadwal->where('kelas_mhs.id_mhs', $id_mhs);

        return $jadwal->get()->getResultObject();
    }

    /**
     * Check user registered to a class
     * 
     * @param int $id_mhs
     * 
     * @return bool
     */
    public function isRegisteredToClass() {}

    /**
     * Get id of mahasiswa by id of user
     * 
     * @param int $idUser
     * 
     * @return int|null
     */
    public function getMahasiswaID(int $idUser) : ?int
    {
        $data = $this->builder('mahasiswa')
            ->select('id')
            ->where('id_user', $idUser)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();

        return !is_null($data) ? $data->id : null;
    }

}