<?php

namespace Modules\Admin\Master\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class MatkulModel extends Model
{

    private $tblName = 'matkul';

    private $columnOrder   = [null, 'kode', 'nama', 'created_at', null];
    private $columnSearch  = ['kode', 'nama'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get matkul list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams) : ?array
    {
        $matkul = $this->builder($this->tblName);
        foreach($this->columnSearch as $idx => $columnSearch) {
            if (isset($dtParams['search']) && !empty($dtParams['search'])) {
                if ($idx == 0) {
                    $matkul->groupStart();
                    $matkul->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $matkul->orLike($columnSearch, $dtParams['search']['value']);
                }

                if (count($this->columnSearch) - 1 === $idx) {
                    $matkul->groupEnd();
                }
            }
        }

        if (isset($dtParams['order'])) {
            $matkul->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $matkul->limit($dtParams['length'], $dtParams['start']);
            }
        }

        return $matkul->get()->getResultObject();
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
        $matkul = $this->builder($this->tblName);
        foreach($this->columnSearch as $idx => $columnSearch) {
            if (isset($dtParams['search']) && !empty($dtParams['search'])) {
                if ($idx == 0) {
                    $matkul->groupStart();
                    $matkul->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $matkul->orLike($columnSearch, $dtParams['search']['value']);
                }

                if (count($this->columnSearch) - 1 === $idx) {
                    $matkul->groupEnd();
                }
            }
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $matkul->limit($dtParams['length'], $dtParams['start']);
            }
        }

        return $matkul->countAllResults();
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
     * Get list of available matkul
     * 
     * @return array
     */
    public function getAvailableMatkul()
    {
        $matkul = $this->builder($this->tblName);
        $matkul->select('
            matkul.id,
            matkul.kode,
            matkul.nama
        ');
        $matkul->join('kelas', 'kelas.id_matkul = matkul.id', 'left');
        $matkul->whereNotIn('matkul.id', function(BaseBuilder $baseBuilder) {
            return $baseBuilder->select('id_matkul')->from('kelas')->where('deleted_at', null);
        });

        return $matkul->get()
            ->getResultObject();
    }

    /**
     * Get all matkul
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->builder($this->tblName)
            ->get()
            ->getResultObject();
    }

}