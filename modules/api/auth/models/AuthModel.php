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
            ->select('id, username, password, email, level, token')
            ->where('username', $username)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();
        
        return $result;
    }

    /**
     * Update user token
     * 
     * @param string $username
     * @param string $token
     * 
     * @return bool
     */
    public function updateToken(string $username, string $token) : bool
    {
        $result = $this->builder('users')
            ->set('token', $token)
            ->where('username', $username)
            ->update();
        
        return $result;
    }

    /**
     * Update last login
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function updateLastLogin(string $username) : bool
    {
        $result = $this->builder('users')
            ->set('last_login', date('Y-m-d H:i:s'))
            ->where('username', $username)
            ->update();

        return $result;
    }
}
