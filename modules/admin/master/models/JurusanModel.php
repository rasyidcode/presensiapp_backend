<?php

namespace Modules\Admin\Master\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{

    private $tblName = 'jurusan';

    private $columnOrder   = [null, 'kode', 'nama', 'created_at', null];
    private $columnSearch  = ['kode', 'nama'];

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

    /**
     * Get list of active jurusan
     * 
     * @return array
     */
    public function getList()
    {
        return $this->builder($this->tblName)
            ->select('id, kode, nama')
            ->get()
            ->getResultObject();
    }

    /**
     * Get jurusan by id
     * 
     * @param int $id
     * 
     * @return object
     */
    public function getByID(int $id) : ?object
    {
        return $this->builder($this->tblName)
            ->where('id', $id)
            ->get()
            ->getRowObject();
    }

    /**
     * Get jurusan by kode where ID not equal to param $id
     * 
     * @param int $id
     * 
     * @return object
     */
    public function getByKode(string $kode, int $id) : ?object
    {
        return $this->builder($this->tblName)
            ->where('kode', $kode)
            ->where('id <>', $id)
            ->get()
            ->getRowObject();
    }

    /**
     * Update jurusan by ID
     * 
     * @param array $data
     * @param int $id
     * 
     * @return void
     */
    public function updateJurusan(array $data, int $id)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->builder($this->tblName)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete jurusan by ID
     * 
     * @param int $id
     * 
     * @return void
     */
    public function deleteJurusan(int $id)
    {
        $this->builder($this->tblName)
            ->where('id', $id)
            ->delete();
    }

}