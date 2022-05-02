<?php

namespace Modules\Admin\Master\Models;

use CodeIgniter\Model;

class MatkulModel extends Model
{

    private $tblName = 'matkul';

    private $columnOrder   = [null, 'kode_matkul', 'nama_matkul', 'created_at', null];
    private $columnSearch  = ['kode_matkul', 'nama_matkul'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get jurusan list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams) : ?array
    {
        $jurusan = $this->builder($this->tblName);
        foreach($this->columnSearch as $idx => $columnSearch) {
            if (isset($dtParams['search']) && !empty($dtParams['search'])) {
                if ($idx == 0) {
                    $jurusan->groupStart();
                    $jurusan->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $jurusan->orLike($columnSearch, $dtParams['search']['value']);
                }

                if (count($this->columnSearch) - 1 === $idx) {
                    $jurusan->groupEnd();
                }
            }
        }

        if (isset($dtParams['order'])) {
            $jurusan->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $jurusan->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $jurusan->where('deleted_at', null);

        return $jurusan->get()->getResultObject();
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
        $jurusan = $this->builder($this->tblName);
        foreach($this->columnSearch as $idx => $columnSearch) {
            if (isset($dtParams['search']) && !empty($dtParams['search'])) {
                if ($idx == 0) {
                    $jurusan->groupStart();
                    $jurusan->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $jurusan->orLike($columnSearch, $dtParams['search']['value']);
                }

                if (count($this->columnSearch) - 1 === $idx) {
                    $jurusan->groupEnd();
                }
            }
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $jurusan->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $jurusan->where('deleted_at', null);

        return $jurusan->countAllResults();
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
     * Create new jurusan
     * 
     * @param array $data
     */
    public function create(array $data)
    {
        $this->db->table($this->tblName)
            ->insert($data);
    }

}