<?php

namespace Modules\Admin\Kelas\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{

    private $tblName = 'kelas';

    private $columnOrder   = [null, 'nama_kelas', 'dosen_pengajar', 'created_at', null];
    private $columnSearch  = ['nama_kelas', 'dosen_pengajar'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get kelas list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams) : ?array
    {
        $kelas = $this->builder($this->tblName);

        $kelas->select('
            kelas.id,
            kelas.created_at,
            dosen.nama_lengkap as dosen_pengajar,
            matkul.kode_matkul as nama_kelas,
            matkul.nama_matkul as nama_matkul
        ');

        $kelas->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $kelas->join('matkul', 'kelas.id_matkul = matkul.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            foreach($this->columnSearch as $idx => $columnSearch) {
                if ($idx == 0) {
                    $kelas->groupStart();
                    $kelas->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $kelas->orLike($columnSearch, $dtParams['search']['value']);   
                }
    
                if (count($this->columnSearch) - 1 === $idx) {
                    $kelas->groupEnd();
                }
            }       
        }

        if (isset($dtParams['order'])) {
            $kelas->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $kelas->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $kelas->where('deleted_at', null);

        return $kelas->get()->getResultObject();
    }

    /**
     * count filtered data
     * 
     * @param array $dtParams
     * 
     * @return int|null
     */
    public function countFilteredData(array $dtParams) : int
    {
        $kelas = $this->builder($this->tblName);

        $kelas->select('
            kelas.id,
            kelas.created_at,
            dosen.nama_lengkap as dosen_pengajar,
            matkul.kode_matkul as nama_kelas,
            matkul.nama_matkul as nama_matkul
        ');

        $kelas->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $kelas->join('matkul', 'kelas.id_matkul = matkul.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            foreach($this->columnSearch as $idx => $columnSearch) {
                if ($idx == 0) {
                    $kelas->groupStart();
                    $kelas->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $kelas->orLike($columnSearch, $dtParams['search']['value']);
                }
    
                if (count($this->columnSearch) - 1 === $idx) {
                    $kelas->groupEnd();
                }
            }
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $kelas->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $kelas->where('deleted_at', null);

        return $kelas->countAllResults();
    }

    /**
     * count total data
     * 
     * @param array $dt_params
     * 
     * @return int
     */
    public function countData() : int
    {
        return $this->builder($this->tblName)
                    ->countAllResults();
    }

    /**
     * Create new mahasiswa
     * 
     * @param array $data
     */
    public function create(array $data)
    {
        $this->db->table($this->tblName)
            ->insert($data);
    }

}