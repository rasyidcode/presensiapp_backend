<?php

namespace Modules\Pub\Login\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user by username
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