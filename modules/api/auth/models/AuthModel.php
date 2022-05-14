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
     * @return object|null
     */
    public function getUser(string $username) : ?object
    {
        $result = $this->builder('users')
            ->select('id, username, password, email, level')
            ->where('username', $username)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();

        return $result;
    }

    /**
     * Get user by it's refresh token
     * 
     * @param string $refreshToken
     * 
     * return object|null
     */
    public function getUserByRf(string $refreshToken) : ?object
    {
        $result = $this->builder('users')
            ->where('token', $refreshToken)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();
        return $result;
    }

    /**
     * Get mahasiswa data by username
     * 
     * @param string $username
     * 
     * @return object
     */
    public function getMahasiswa(string $username) : ?object
    {
        $mahasiswa = $this->builder('users');
        $mahasiswa->select('
            users.id,
            users.username,
            users.password,
            users.email,
            mahasiswa.nama_lengkap,
            '
        );
        $mahasiswa->join('mahasiswa', 'users.id = mahasiswa.id_user', 'left');
        $mahasiswa->where('users.username', $username);
        return $mahasiswa->get()->getRowObject();
    }

    /**
     * Get dosen data by username
     */
    public function getDosen(string $username) : ?object
    {
        $dosen = $this->builder('users');
        $dosen->select('
            users.id,
            users.username,
            users.password,
            users.email,
            dosen.nama_lengkap,
            '
        );
        $dosen->join('dosen', 'users.id = dosen.id_user', 'left');
        $dosen->where('users.username', $username);

        return $dosen->get()->getRowObject();
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
            ->where('deleted_at', null)
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

    /**
     * Delete user refresh token
     * 
     * @param string $username
     * 
     * @return bool
     */
    public function deleteToken(string $username): bool
    {
        $result = $this->builder('users')
            ->set('token', null)
            ->where('username', $username)
            ->update();

        return $result;
    }

    /**
     * Remove token by user's refresh token
     * 
     * @param string $refreshToken
     * 
     * @return bool
     */
    public function removeToken(string $refreshToken): bool
    {
        $result = $this->builder('users')
            ->set('token', null)
            ->where('token', $refreshToken)
            ->update();

        return $result;
    }

    /**
     * Get mahasiswa by user_id
     * 
     * @param int $userId
     * 
     * @return object|null
     */
    public function getDataMahasiswa(int $userId) : ?object
    {
        $result = $this->builder('mahasiswa')
            ->where('id_user', $userId)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();
        return $result;
    }

    /**
     * Get dosen by user_id
     * 
     * @param int $userId
     * 
     * @return object|null
     */
    public function getDataDosen(int $userId)
    {
        $result = $this->builder('dosen')
            ->where('id_user', $userId)
            ->where('deleted_at', null)
            ->get()
            ->getRowObject();
        return $result;
    }
}
