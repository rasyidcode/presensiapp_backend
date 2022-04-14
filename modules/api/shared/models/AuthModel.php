<?php

namespace Modules\Api\Mhs\Models;

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
     * Get mahasiswa data by username
     * 
     * @param string $username
     * 
     * @return object
     */
    public function getMahasiswa(string $username) : ?object
    {
        $this->builder('users')->select('
            users.username,
            users.password,
            users.email,
            mahasiswa.nama_lengkap,
            '
        );
        $this->builder('users')->join('mahasiswa', 'users.id = mahasiswa.id_user', 'left');
        $this->builder('users')->where('username', $username);

        $data = $this->builder('users')->get();
        return $data->getRowObject();
    }

    /**
     * Get dosen data by username
     */
    public function getDosen(string $username)
    {
        $this->builder('users')->select('
            users.id,
            users.username, 
            users.email,
            users.level,
            dosen.nama_lengkap, 
            dosen.nip,
            '
        );
        $this->builder('users')->join('dosen', 'users.id = dosen.id_user', 'left');
        $this->builder('users')->where('username', $username);
        $data = $this->builder('users')->get();
        return $data->getResultArray();
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
}
