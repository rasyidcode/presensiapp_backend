<?php

namespace Modules\Api\Dosen\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDosenID(int $idUser)
    {
        $dosen = $this
            ->builder('dosen')
            ->select('id')
            ->where('id_user', $idUser)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();
        
        return !is_null($dosen) ? $dosen->id : null;
    }

    public function getListPerkuliahan(int $dosenId) : array
    {
        $jadwal = $this->builder('jadwal');

        $jadwal->select('
            jadwal.id as id_jadwal,
            jadwal.date as tgl_jadwal,

            matkul.kode as kode_matkul,
            matkul.nama as nama_matkul,

            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,

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
            ) as status_perkuliahan,
            dosen_qrcode.qr_secret
        ');

        $jadwal->join('dosen_qrcode', 'dosen_qrcode.id_jadwal = jadwal.id', 'left');
        $jadwal->join('kelas', 'kelas.id = jadwal.id_kelas', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');

        $jadwal->where('kelas.id_dosen', $dosenId);
        $jadwal->where('jadwal.date', date('Y-m-d'));
        $jadwal->where('jadwal.deleted_at', null);


        return $jadwal
            ->get()
            ->getResultObject();
    }

    public function getPerkuliahan(int $idJadwal) : ?object
    {
        $jadwal = $this->builder('jadwal');

        $jadwal->select('
            jadwal.id as id_jadwal,
            jadwal.date as tgl_jadwal,

            matkul.kode as kode_matkul,
            matkul.nama as nama_matkul,

            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,

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
            ) as status_perkuliahan,
            dosen_qrcode.qr_secret
        ');

        $jadwal->join('dosen_qrcode', 'dosen_qrcode.id_jadwal = jadwal.id', 'left');
        $jadwal->join('kelas', 'kelas.id = jadwal.id_kelas', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');

        $jadwal->where('jadwal.id', $idJadwal);
        $jadwal->where('jadwal.deleted_at', null);
        

        return $jadwal
            ->get()
            ->getRowObject();
    }

    public function postQR(array $data)
    {
        $this
            ->builder('dosen_qrcode')
            ->insert($data);

        return $this
            ->db
            ->insertID();
    }

    public function getListPresensi(int $idJadwal) : array
    {
        $listPresensi = $this
            ->builder('presensi')
            ->select('
                presensi.id,
                mahasiswa.nim,
                mahasiswa.nama_lengkap,
                presensi.status_presensi,
                presensi.created_at
            ')
            ->join('dosen_qrcode', 'dosen_qrcode.id = presensi.id_dosen_qrcode', 'left')
            ->join('mahasiswa', 'mahasiswa.id = presensi.id_mahasiswa', 'left')
            ->where('dosen_qrcode.id_jadwal', $idJadwal)
            ->get()
            ->getResultObject();
        return $listPresensi;
    }

    public function getListMahasiswaIDs(int $idJadwal) : array
    {
        $mhs = $this
            ->builder('jadwal')
            ->select('kelas_mahasiswa.id_mahasiswa')
            ->join('kelas', 'jadwal.id_kelas = kelas.id', 'left')
            ->join('kelas_mahasiswa', 'kelas.id = kelas_mahasiswa.id_kelas', 'left')
            ->where('jadwal.id', $idJadwal)
            ->get()
            ->getResultObject();
        $ids = [];

        if (count($mhs) > 0) {
            foreach($mhs as $m) {
                $ids[] = (int) $m->id_mahasiswa;
            }
        }

        return $ids;
    }

    public function getJadwal(int $idJadwal, int $idDosen) : ?object
    {
        return $this
            ->builder('jadwal')
            ->select('
                jadwal.id,
                jadwal.date,
                SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
                SUBSTR(jadwal.end_time, 1, 5) as end_time')
            ->join('kelas', 'jadwal.id_kelas = kelas.id', 'left')
            ->where('jadwal.id', $idJadwal)
            ->where('kelas.id_dosen', $idDosen)
            ->get()
            ->getRowObject();
    }

    public function getQRCode(int $idJadwal) : ?object
    {
        return $this
            ->builder('dosen_qrcode')
            ->select('*')
            ->where('id_jadwal', $idJadwal)
            ->get()
            ->getRowObject();
    }

    public function initPresensiMahasiswa(array $dataPresensi)
    {
        $this
            ->builder('presensi')
            ->insertBatch($dataPresensi);
    }

    public function getPresensi(int $idMhs, int $idJadwal) : ?object
    {
        return $this
            ->builder('jadwal')
            ->select('
                presensi.id,
                presensi.id_dosen_qrcode,
                presensi.id_mahasiswa,
                presensi.status_presensi,
                dosen_qrcode.qr_secret,
                dosen_qrcode.id_jadwal
            ')
            ->join('dosen_qrcode', 'dosen_qrcode.id_jadwal = jadwal.id', 'left')
            ->join('presensi', 'dosen_qrcode.id = presensi.id_dosen_qrcode')
            ->where('presensi.id_mahasiswa', $idMhs)
            ->where('dosen_qrcode.id_jadwal', $idJadwal)
            ->get()
            ->getRowObject();
    }

    public function updatePresensi(int $idMhs, int $idDosenQrCode, int $statusPresensi)
    {
        $this
            ->builder('presensi')
            ->where('id_mahasiswa', $idMhs)
            ->where('id_dosen_qrcode', $idDosenQrCode)
            ->update([
                'status_presensi'   => $statusPresensi
            ]);
    }
}