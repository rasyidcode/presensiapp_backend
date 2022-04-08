<?php

namespace Modules\Admin\Users\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

    private $tbl_name = 'users';

    private $columns_order   = [null, 'username', 'email', 'level', 'last_login', 'created_at', null];
    private $columns_search  = ['username', 'email', 'level'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user list with datatable params
     * 
     * @param array $dt_params
     * 
     * @return array|null
     */
    public function getData(array $dt_params) : ?array
    {
        foreach($this->columns_search as $idx => $column_search) {
            if (isset($dt_params['search']) && !empty($dt_params['search'])) {
                if ($idx == 0) {
                    $this->builder($this->tbl_name)
                         ->groupStart();
                    $this->builder($this->tbl_name)
                         ->like($column_search, $dt_params['search']['value']);
                } else {
                    $this->builder($this->tbl_name)
                         ->orLike($column_search, $dt_params['search']['value']);
                }

                if (count($this->columns_search) - 1 === $idx) {
                    $this->builder($this->tbl_name)
                         ->groupEnd();
                }
            }
        }

        if (isset($dt_params['order'])) {
            $this->builder($this->tbl_name)
                 ->orderBy($this->columns_order[$dt_params['order']['0']['column']], $dt_params['order']['0']['dir']);
        }

        if (isset($dt_params['length']) && isset($dt_params['start'])) {
            if ($dt_params['length'] !== -1) {
                $this->builder($this->tbl_name)
                     ->limit($dt_params['length'], $dt_params['start']);
            }
        }

        $this->builder($this->tbl_name)->where('deleted_at', null);

        $result = $this->builder($this->tbl_name)
                       ->get();
        return $result->getResultArray();
    }

    /**
     * count datatable data
     * 
     * @param array $dt_params
     * 
     * @return int|null
     */
    public function countData(array $dt_params) : ?int
    {
        foreach($this->columns_search as $idx => $column_search) {
            if (isset($dt_params['search']) && !empty($dt_params['search'])) {
                if ($idx == 0) {
                    $this->builder($this->tbl_name)
                         ->groupStart();
                    $this->builder($this->tbl_name)
                         ->like($column_search, $dt_params['search']['value']);
                } else {
                    $this->builder($this->tbl_name)
                         ->orLike($column_search, $dt_params['search']['value']);
                }

                if (count($this->columns_search) - 1 === $idx) {
                    $this->builder($this->tbl_name)
                         ->groupEnd();
                }
            }
        }

        if (isset($dt_params['length']) && isset($dt_params['start'])) {
            if ($dt_params['length'] !== -1) {
                $this->builder($this->tbl_name)
                     ->limit($dt_params['length'], $dt_params['start']);
            }
        }

        $this->builder($this->tbl_name)
             ->where('deleted_at', null);

        return $this->builder($this->tbl_name)
                    ->countAllResults();
    }

    /**
     * count filtered data
     * 
     * @param array $dt_params
     * 
     * @return int
     */
    public function countFilteredData() : ?int
    {
        return $this->builder($this->tbl_name)
                    ->countAllResults();
    }
}