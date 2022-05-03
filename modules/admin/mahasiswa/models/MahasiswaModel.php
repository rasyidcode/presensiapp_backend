<?php

namespace Modules\Admin\Mahasiswa\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{

    private $tblName = 'mahasiswa';

    private $columnOrder   = [null, 'nim', 'nama_lengkap', 'jurusan', 'tahun_masuk', 'jenis_kelamin', 'created_at', null];
    private $columnSearch  = ['nim', 'nama_lengkap', 'jurusan'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get mahasiswa list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams) : ?array
    {
        $mahasiswa = $this->builder($this->tblName);

        $mahasiswa->select('
            mahasiswa.id,
            mahasiswa.nim,
            mahasiswa.nama_lengkap,
            jurusan.nama_jurusan,
            mahasiswa.tahun_masuk,
            mahasiswa.jenis_kelamin,
            mahasiswa.created_at
        ');

        $mahasiswa->join('jurusan', 'mahasiswa.id_jurusan = jurusan.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            foreach($this->columnSearch as $idx => $columnSearch) {
                if ($idx == 0) {
                    $mahasiswa->groupStart();
                    $mahasiswa->like($columnSearch, $dtParams['search']['value']);
                } else {
                    if ($columnSearch == 'jurusan') {
                        $mahasiswa->orlike('jurusan.nama_jurusan', $dtParams['search']['value']);
                    } else {
                        $mahasiswa->orLike($columnSearch, $dtParams['search']['value']);   
                    }
                }
    
                if (count($this->columnSearch) - 1 === $idx) {
                    $mahasiswa->groupEnd();
                }
            }       
        }

        if (isset($dtParams['order'])) {
            $mahasiswa->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $mahasiswa->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $mahasiswa->where('mahasiswa.deleted_at', null);

        return $mahasiswa->get()->getResultObject();
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
        $mahasiswa = $this->builder($this->tblName);

        $mahasiswa->select('
            mahasiswa.id,
            mahasiswa.nim,
            mahasiswa.nama_lengkap,
            jurusan.nama_jurusan,
            mahasiswa.tahun_masuk,
            mahasiswa.jenis_kelamin,
            mahasiswa.created_at
        ');

        $mahasiswa->join('jurusan', 'mahasiswa.id_jurusan = jurusan.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            foreach($this->columnSearch as $idx => $columnSearch) {
                if ($idx == 0) {
                    $mahasiswa->groupStart();
                    $mahasiswa->like($columnSearch, $dtParams['search']['value']);
                } else {
                    if ($columnSearch == 'jurusan') {
                        $mahasiswa->orlike('jurusan.nama_jurusan', $dtParams['search']['value']);
                    } else {
                        $mahasiswa->orLike($columnSearch, $dtParams['search']['value']);   
                    }
                }
    
                if (count($this->columnSearch) - 1 === $idx) {
                    $mahasiswa->groupEnd();
                }
            }
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $mahasiswa->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $mahasiswa->where('mahasiswa.deleted_at', null);

        return $mahasiswa->countAllResults();
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