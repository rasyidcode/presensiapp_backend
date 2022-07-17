<?php

namespace Modules\Api\Perkuliahan\Models;

use CodeIgniter\Model;

class PerkuliahanModel extends Model
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
    public function getList(int $id_mhs)
    {
        $jadwal = $this->builder('jadwal');

        $jadwal->select('
            jadwal.id,
            dosen.nip as nip_dosen,
            dosen.nama_lengkap as nama_dosen,

            matkul.kode as nama_kelas,
            matkul.nama as matkul,

            jadwal.date,
            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,
            dosen_qrcode.qr_secret,
            SUBSTR(curtime(), 1, 5) as now_time,
            IF(
                presensi.status_presensi=0,
                \'absen\', 
                IF(
                    presensi.status_presensi=1,
                    \'present\', 
                    IF(
                        presensi.status_presensi=2,
                        \'late\',
                        null
                    )
                )
            ) as status_presensi,
            IF(
                curdate()=jadwal.date,
                IF(
                    curtime()<jadwal.begin_time,
                    \'not_started\',
                    IF(
                        curtime()>=jadwal.begin_time,
                        IF(
                            curtime()<=jadwal.end_time,
                            \'ongoing\',
                            IF(curtime()>jadwal.end_time,
                            \'done\',
                            null
                            )
                        ),
                        null
                    )
                ),
                IF(
                    curdate()>jadwal.date,
                    \'done\',
                    \'not_started\'
                )
            ) as status_perkuliahan
        ');

        $jadwal->join('kelas', 'jadwal.id_kelas = kelas.id', 'left');
        $jadwal->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');
        $jadwal->join('kelas_mahasiswa', 'kelas.id = kelas_mahasiswa.id_kelas', 'left');
        $jadwal->join('dosen_qrcode', 'jadwal.id = dosen_qrcode.id_jadwal', 'left');
        $jadwal->join('presensi', 'dosen_qrcode.id = presensi.id_dosen_qrcode', 'left');

        $jadwal->where('jadwal.date', date('Y-m-d'));
        $jadwal->where('jadwal.deleted_at', null);
        $jadwal->where('kelas_mahasiswa.id_mahasiswa', $id_mhs);

        $jadwal->orderBy('jadwal.begin_time', 'asc');
        $jadwal->orderBy('jadwal.end_time', 'asc');

        return $jadwal
            ->get()
            ->getResultObject();
    }

    /**
     * Get detail perkuliahan by id
     * 
     * @param int $id
     * 
     * @return object|null
     */
    public function getOne(int $id): ?object
    {
        $jadwal = $this->builder('jadwal');

        $jadwal->select('
            jadwal.id,
            dosen.nip as nip_dosen,
            dosen.nama_lengkap as nama_dosen,

            matkul.kode as nama_kelas,
            matkul.nama as matkul,

            jadwal.date,
            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,
            dosen_qrcode.qr_secret,
            SUBSTR(curtime(), 1, 5) as now_time,
            IF(
                presensi.status_presensi=0,
                \'absen\', 
                IF(
                    presensi.status_presensi=1,
                    \'present\', 
                    IF(
                        presensi.status_presensi=2,
                        \'late\',
                        null
                    )
                )
            ) as status_presensi,
            IF(
                curdate()=jadwal.date,
                IF(
                    curtime()<jadwal.begin_time,
                    \'not_started\',
                    IF(
                        curtime()>=jadwal.begin_time,
                        IF(
                            curtime()<=jadwal.end_time,
                            \'ongoing\',
                            IF(curtime()>jadwal.end_time,
                            \'done\',
                            null
                            )
                        ),
                        null
                    )
                ),
                IF(
                    curdate()>jadwal.date,
                    \'done\',
                    \'not_started\'
                )
            ) as status_perkuliahan
        ');

        $jadwal->join('kelas', 'jadwal.id_kelas = kelas.id', 'left');
        $jadwal->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');
        $jadwal->join('kelas_mahasiswa', 'kelas.id = kelas_mahasiswa.id_kelas', 'left');
        $jadwal->join('dosen_qrcode', 'jadwal.id = dosen_qrcode.id_jadwal', 'left');
        $jadwal->join('presensi', 'dosen_qrcode.id = presensi.id_dosen_qrcode', 'left');

        $jadwal->where('jadwal.deleted_at', null);
        $jadwal->where('jadwal.id', $id);

        return $jadwal->get()
            ->getRowObject();
    }

    /**
     * Check user registered to a class
     * 
     * @param int $id_mhs
     * 
     * @return bool
     */
    public function isRegisteredToClass()
    {
    }

    /**
     * Get id of mahasiswa by id of user
     * 
     * @param int $idUser
     * 
     * @return int|null
     */
    public function getMahasiswaID(int $idUser): ?int
    {
        $data = $this
            ->builder('mahasiswa')
            ->select('id')
            ->where('id_user', $idUser)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();

        return !is_null($data) ? $data->id : null;
    }

    /**
     * Get dosen qrcode by qrsecret
     * 
     * @param string $qrsecret
     * 
     * @return object|null
     */
    public function getDosenQR(string $qrsecret): ?object
    {
        $data = $this
            ->builder('dosen_qrcode')
            ->select('
                dosen_qrcode.*,
                jadwal.id as id_jadwal,
                jadwal.id_kelas,
                SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
                SUBSTR(jadwal.end_time, 1, 5) as end_time,
                jadwal.date
            ')
            ->join('jadwal', 'dosen_qrcode.id_jadwal = jadwal.id', 'left')
            ->where('qr_secret', $qrsecret)
            ->get()
            ->getRowObject();
        return $data;
    }

    /**
     * Check if mahasiswa is registered to certain kelas
     * 
     * @param string $mahasiswaId
     * @param string $kelasId
     * 
     * @return bool
     */
    public function checkMahasiswaRegisteredToKelas(int $mahasiswaId, int $kelasId)
    {
        $result = $this
            ->builder('kelas_mahasiswa')
            ->selectCount('*', 'count')
            ->where('id_kelas', $kelasId)
            ->where('id_mahasiswa', $mahasiswaId)
            ->get()
            ->getRowObject();
        
        return is_null($result) ? false : (isset($result->count) ? ($result->count > 0) : false);
    }

    /**
     * New presensi
     * 
     * @param array $data
     * 
     * @return void
     */
    public function doPresensi(array $data)
    {
        $this
            ->builder('presensi')
            ->insert($data);
    }

    /**
     * Get presensi by dosen_qrcode_id and mahasiswa_id
     * 
     * @param int dosenQrCodeId
     * @param int mahasiswaId
     * 
     * @return object|null
     */
    public function getPresensi(int $dosenQrCodeId, int $mahasiswaId) : ?object
    {
        $result = $this
            ->builder('presensi')
            ->where('id_dosen_qrcode', $dosenQrCodeId)
            ->where('id_mahasiswa', $mahasiswaId)
            ->get()
            ->getRowObject();
        return $result;
    }

}
