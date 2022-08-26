<?php

namespace Modules\Admin\Jadwal\Models;

use CodeIgniter\Model;
use DateInterval;
use DatePeriod;
use DateTime;

class JadwalModel extends Model
{

    private $tblName = 'jadwal';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jadwal list by given day with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams): ?array
    {
        $searchArr = explode('|', $dtParams['search']['value']);
        $dosenId = explode('=', $searchArr[0])[1];
        $matkulId = explode('=', $searchArr[1])[1];
        $dow = explode('=', $searchArr[2])[1];
        $dayofweek = null;
        if ($dow == 0) {
            $dayofweek = $dow;
        } else {
            $dayofweek = is_null($dow) || empty($dow) ? 1 : $dow;
        }
        
        $jadwal = $this->builder($this->tblName);

        $jadwal->select('
            jadwal.id,
            jadwal.date,
            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,
            matkul.nama as matkul,
            dosen.nama_lengkap as dosen,
            jadwal.created_at,
            jadwal.updated_at,
            count(kelas_mahasiswa.id_mahasiswa) as mahasiswa_total 
        ');
        
        $jadwal->join('kelas', 'jadwal.id_kelas = kelas.id', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');
        $jadwal->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $jadwal->join('kelas_mahasiswa', 'kelas.id = kelas_mahasiswa.id_kelas', 'left');

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $jadwal->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $dates = $this->getDatesByDOW($dayofweek);
        
        if (!empty($dates)) {
            $jadwal->whereIn('jadwal.date', $dates);
        }

        $jadwal->where('jadwal.deleted_at', null);

        if (!empty($dosenId)) {
            $jadwal->where('kelas.id_dosen', $dosenId);
        }

        if (!empty($matkulId)) {
            $jadwal->where('kelas.id_matkul', $matkulId);
        }

        $jadwal->orderBy('jadwal.date', 'asc');
        $jadwal->groupBy('jadwal.id');

        return $jadwal
            ->get()
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
        $searchArr = explode('|', $dtParams['search']['value']);
        $dosenId = explode('=', $searchArr[0])[1];
        $matkulId = explode('=', $searchArr[1])[1];
        $dow = explode('=', $searchArr[2])[1];
        $dayofweek = null;
        if ($dow == 0) {
            $dayofweek = $dow;
        } else {
            $dayofweek = is_null($dow) || empty($dow) ? 1 : $dow;
        }

        $jadwal = $this->builder($this->tblName);

        $jadwal->select('
            jadwal.id,
            jadwal.date,
            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,
            matkul.nama as matkul,
            dosen.nama_lengkap as dosen,
            jadwal.created_at,
            jadwal.updated_at,
            count(kelas_mahasiswa.id_mahasiswa) as mahasiswa_total 
        ');

        $jadwal->join('kelas', 'jadwal.id_kelas = kelas.id', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');
        $jadwal->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $jadwal->join('kelas_mahasiswa', 'kelas.id = kelas_mahasiswa.id_kelas', 'left');

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $jadwal->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $dates = $this->getDatesByDOW($dayofweek);
        if (!empty($dates)) {
            $jadwal->whereIn('jadwal.date', $dates);
        }
        $jadwal->where('jadwal.deleted_at', null);

        if (!empty($dosenId)) {
            $jadwal->where('kelas.id_dosen', $dosenId);
        }

        if (!empty($matkulId)) {
            $jadwal->where('kelas.id_matkul', $matkulId);
        }

        $jadwal->orderBy('jadwal.date', 'asc');
        $jadwal->groupBy('jadwal.id');

        return $jadwal->countAllResults();
    }

    /**
     * count total data
     * 
     * @param array $dt_params
     * 
     * @return int
     */
    public function countData($dtParams): int
    {
        $searchArr = explode('|', $dtParams['search']['value']);
        $dosenId = explode('=', $searchArr[0])[1];
        $matkulId = explode('=', $searchArr[1])[1];
        $dow = explode('=', $searchArr[2])[1];
        $dayofweek = null;
        if ($dow == 0) {
            $dayofweek = $dow;
        } else {
            $dayofweek = is_null($dow) || empty($dow) ? 1 : $dow;
        }

        $jadwal = $this->builder($this->tblName);

        $jadwal->select('
            jadwal.id,
            jadwal.date,
            SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
            SUBSTR(jadwal.end_time, 1, 5) as end_time,
            matkul.nama as matkul,
            dosen.nama_lengkap as dosen,
            jadwal.created_at,
            count(kelas_mahasiswa.id_mahasiswa) as mahasiswa_total 
        ');

        $jadwal->join('kelas', 'jadwal.id_kelas = kelas.id', 'left');
        $jadwal->join('matkul', 'kelas.id_matkul = matkul.id', 'left');
        $jadwal->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $jadwal->join('kelas_mahasiswa', 'kelas.id = kelas_mahasiswa.id_kelas', 'left');

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $jadwal->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $dates = $this->getDatesByDOW($dayofweek);
        if (!empty($dates)) {
            $jadwal->whereIn('jadwal.date', $dates);
        }
        $jadwal->where('jadwal.deleted_at', null);

        if (!empty($dosenId)) {
            $jadwal->where('kelas.id_dosen', $dosenId);
        }

        if (!empty($matkulId)) {
            $jadwal->where('kelas.id_matkul', $matkulId);
        }

        $jadwal->orderBy('jadwal.date', 'asc');
        $jadwal->groupBy('jadwal.id');

        return $jadwal->countAllResults();
    }

    /**
     * Create new jadwal
     * 
     * @param array $data
     */
    public function create(array $data)
    {
        $this->db->table($this->tblName)
            ->insert($data);
    }

    /**
     * Get dates of given dayofweek for the next 6 months
     * 
     * @param int $dayofweek
     * 
     * @return array|null
     */
    private function getDatesByDOW(int $dow): array
    {
        $dates = [];

        $checkFirstDate = $this->builder($this->tblName)
            ->select('date')
            ->orderBy('date', 'asc')
            ->get(1)
            ->getRowObject();
        if (is_null($checkFirstDate)) {
            return $dates;
        }
        $firstDate = $checkFirstDate->date;

        $sixMonthsDays = round(365 / 2);
        $endDate = date('Y-m-d', strtotime($firstDate . '+' . $sixMonthsDays . ' day'));
        $first = new DateTime($firstDate);
        $end = new DateTime($endDate);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($first, $interval, $end);

        foreach ($period as $pd) {
            $date = $pd->format("Y-m-d");
            $dayofweek = date('w', strtotime($date));
            if ($dayofweek == $dow) {
                $dates[] = $date;
            }
        }
        return $dates;
    }
}
