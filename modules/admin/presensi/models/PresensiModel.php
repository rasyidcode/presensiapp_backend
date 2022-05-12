<?php

namespace Modules\Admin\Presensi\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{

    private $tblName = 'presensi';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get presensi list by given date and kelasId with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams): ?array
    {
        $keyword = $dtParams['search']['value'];
        $searchData = explode('|', $keyword);
        $date = $searchData[0] ?? date('Y-m-d');
        $kelas = $searchData[1] ?? '';

        $presensi = $this->builder($this->tblName);

        $presensi->select('
            mahasiswa.nama_lengkap,
            presensi.status_presensi,
            presensi.created_at
        ');
        
        $presensi->join('mahasiswa', 'presensi.id_mahasiswa = mahasiswa.id', 'left');
        $presensi->join('dosen_qrcode', 'presensi.id_dosen_qrcode = dosen_qrcode.id', 'left');
        $presensi->join('jadwal', 'dosen_qrcode.id_jadwal = jadwal.id', 'left');

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $presensi->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $presensi->where('jadwal.date', $date);
        if (!empty($kelas)) {
            $presensi->where('jadwal.id_kelas', $kelas);
        }

        return $presensi->get()
            ->getResultObject();
    }

    /**
     * count filtered data
     * 
     * @param array $dtParams
     * 
     * @return int|null
     */
    public function countFilteredData(array $dtParams): int
    {
        $keyword = $dtParams['search']['value'];
        $searchData = explode('|', $keyword);
        $date = $searchData[0] ?? date('Y-m-d');
        $kelas = $searchData[1] ?? '';
        
        $presensi = $this->builder($this->tblName);

        $presensi->select('
            mahasiswa.nama_lengkap,
            presensi.status_presensi,
            presensi.created_at
        ');
        
        $presensi->join('mahasiswa', 'presensi.id_mahasiswa = mahasiswa.id', 'left');
        $presensi->join('dosen_qrcode', 'presensi.id_dosen_qrcode = dosen_qrcode.id', 'left');
        $presensi->join('jadwal', 'dosen_qrcode.id_jadwal = jadwal.id', 'left');

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $presensi->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $presensi->where('jadwal.date', $date);
        if (!empty($kelas)) {
            $presensi->where('jadwal.id_kelas', $kelas);
        }

        return $presensi->countAllResults();
    }

    /**
     * count total data
     * 
     * @param array $dt_params
     * 
     * @return int
     */
    public function countData(array $dtParams): int
    {
        $keyword = $dtParams['search']['value'];
        $searchData = explode('|', $keyword);
        $date = $searchData[0] ?? date('Y-m-d');
        $kelas = $searchData[1] ?? '';
        
        $presensi = $this->builder($this->tblName);

        $presensi->select('
            mahasiswa.nama_lengkap,
            presensi.status_presensi,
            presensi.created_at
        ');
        
        $presensi->join('mahasiswa', 'presensi.id_mahasiswa = mahasiswa.id', 'left');
        $presensi->join('dosen_qrcode', 'presensi.id_dosen_qrcode = dosen_qrcode.id', 'left');
        $presensi->join('jadwal', 'dosen_qrcode.id_jadwal = jadwal.id', 'left');

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $presensi->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $presensi->where('jadwal.date', $date);
        if (!empty($kelas)) {
            $presensi->where('jadwal.id_kelas', $kelas);
        }

        return $presensi->countAllResults();
    }

}