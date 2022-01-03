<?php

namespace Modules\Api\Auth\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user detail by username
     * 
     * @param string username
     * 
     * @return array|null
     */
    public function getUser(string $username) : ?array
    {
        $result = $this->builder('users')
            ->select('id, username, password, email, level')
            ->where('username', $username)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();
        
        return $result;
    }
}
