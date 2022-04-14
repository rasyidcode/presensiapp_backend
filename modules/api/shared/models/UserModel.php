<?php

namespace Modules\Api\Shared\Models;

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

    /**
     * Check if the user is mhs level
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function isMahasiswa(string $username) : bool
    {
        $result = $this->builder('users')
            ->selectCount('*', 'count')
            ->where('username', $username)
            ->where('level', 'mahasiswa')
            ->get()
            ->getRowArray();
    
        return !is_null($result) ? ($result['count'] > 0) : false;
    }

    /**
     * Check if user is dosen level
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function isDosen(string $username) : bool
    {
        $result = $this->builder('users')
            ->selectCount('*', 'count')
            ->where('username', $username)
            ->where('level', 'dosen')
            ->get()
            ->getRowArray();
        return !is_null($result) ? ($result['count'] > 0) : false;
    }

    /**
     * Check if user is admin level
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function isAdmin(string $username) : bool
    {
        $result = $this->builder('users')
            ->selectCount('*', 'count')
            ->where('username', $username)
            ->where('level', 'admin')
            ->get()
            ->getRowArray();
        return !is_null($result) ? ($result['count'] > 0) : false;
    }

}