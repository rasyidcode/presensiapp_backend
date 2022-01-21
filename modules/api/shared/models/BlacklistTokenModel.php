<?php

namespace Modules\Api\Shared\Models;

use CodeIgniter\Model;

class BlacklistTokenModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check token if it exist or not
     * 
     * @param string $token
     * 
     * @return bool
     */
    public function tokenExist(string $token): bool
    {
        $result = $this->builder('blacklist_token')
            ->selectCount('*', 'count')
            ->where('token', $token)
            ->get()
            ->getRowArray();
        
        if (is_null($result))
            return false;
        
        return $result['count'] > 0;
    }

    /**
     * Create a new blacklisted token
     * 
     * @param string $newToken
     * 
     * @return int
     */
    public function addToken(string $token)
    {
        $this->builder('blacklist_token')
            ->insert([
                'token' => $token
            ]);
        return $this->db->insertID() > 0;
    }

    /**
     * Get last inserted id
     * 
     * @return int
     */

}