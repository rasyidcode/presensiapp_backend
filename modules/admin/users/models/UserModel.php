<?php

namespace Modules\Admin\Users\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

    private $tblName = 'users';

    private $columnOrder   = [null, 'username', 'email', 'level', 'last_login', 'created_at', null];
    private $columnSearch  = ['username', 'email', 'level'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams) : ?array
    {
        $user = $this->builder($this->tblName);
        foreach($this->columnSearch as $idx => $columnSearch) {
            if (isset($dtParams['search']) && !empty($dtParams['search'])) {
                if ($idx == 0) {
                    $user->groupStart();
                    $user->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $user->orLike($columnSearch, $dtParams['search']['value']);
                }

                if (count($this->columnSearch) - 1 === $idx) {
                    $user->groupEnd();
                }
            }
        }

        if (isset($dtParams['order'])) {
            $user->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $user->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $user->where('deleted_at', null);

        return $user->get()->getResultObject();
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
        $user = $this->builder($this->tblName);
        foreach($this->columnSearch as $idx => $columnSearch) {
            if (isset($dtParams['search']) && !empty($dtParams['search'])) {
                if ($idx == 0) {
                    $user->groupStart();
                    $user->like($columnSearch, $dtParams['search']['value']);
                } else {
                    $user->orLike($columnSearch, $dtParams['search']['value']);
                }

                if (count($this->columnSearch) - 1 === $idx) {
                    $user->groupEnd();
                }
            }
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $user->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $user->where('deleted_at', null);

        return $user->countAllResults();
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
     * Create new user
     * 
     * @param array $data
     */
    public function create(array $data)
    {
        $this->db->table($this->tblName)
            ->insert($data);
    }
}