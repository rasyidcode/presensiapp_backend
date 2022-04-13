<?php

namespace Modules\Api\User\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check user by username
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function checkUser(string $username): bool
    {
        $result = $this->builder('users')
            ->selectCount('*', 'count')
            ->where('username', $username)
            ->get()
            ->getRowArray();
        
        if (is_null($result))
            return false;

        return $result['count'] > 0 ? true : false;
    }

    /**
     * Get user by username
     * 
     * @param string $username
     * 
     * @return null|object
     */
    // public function getUser(string $username) : ?object
    // {
    //     $result = $this->builder('users')
    //         ->select('')
    // }

    /**
     * Remove user token by username
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function removeToken(string $username): bool
    {
        $result = $this->builder('users')
            ->set('token', null)
            ->where('username', $username)
            ->update();
        return $result;
    }

    /**
     * Get user by refreshtoken
     * 
     * @param string $refreshToken
     * 
     * @return bool
     */
    public function checkUserByRt(string $refreshToken): bool
    {
        $result = $this->builder('users')
            ->selectCount('*', 'count')
            ->where('token', $refreshToken)
            ->get()
            ->getRowArray();
        
        if (is_null($result))
            return false;

        return $result['count'] > 0;
    }
}