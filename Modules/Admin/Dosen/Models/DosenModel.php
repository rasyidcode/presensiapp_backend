<?php

namespace Modules\Admin\Dosen\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{

    private $tblName = 'dosen';

    private $columnOrder   = [null, 'nip', 'nama_lengkap', 'tahun_masuk', 'jenis_kelamin', 'created_at', null];
    private $columnSearch  = ['nip', 'nama_lengkap'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get dosen list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams) : ?array
    {
        $dosen = $this->builder($this->tblName);

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            foreach($this->columnSearch as $idx => $columnSearch) {
                if ($idx == 0) {
                    $dosen->groupStart();
                    $dosen->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $dosen->orLike($columnSearch, $dtParams['search']['value']);   
                }
    
                if (count($this->columnSearch) - 1 === $idx) {
                    $dosen->groupEnd();
                }
            }       
        }

        if (isset($dtParams['order'])) {
            $dosen->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $dosen->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $dosen->where('deleted_at', null);

        return $dosen->get()
            ->getResultObject();
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
        $dosen = $this->builder($this->tblName);

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            foreach($this->columnSearch as $idx => $columnSearch) {
                if ($idx == 0) {
                    $dosen->groupStart();
                    $dosen->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $dosen->orLike($columnSearch, $dtParams['search']['value']);
                }
    
                if (count($this->columnSearch) - 1 === $idx) {
                    $dosen->groupEnd();
                }
            }
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $dosen->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $dosen->where('deleted_at', null);

        return $dosen->countAllResults();
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

    /**
     * Get list of dosen
     * 
     * @return array
     */
    public function getList()
    {
        return $this->builder($this->tblName)
            ->select('id, nama_lengkap')
            ->where('deleted_at', null)
            ->get()
            ->getResultObject();
    }

}