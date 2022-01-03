<?php

namespace Modules\Api\User\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\User\Models\UserModel;

class UserController extends \App\Controllers\BaseController
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    public function index()
    {
        
    }

    public function get()
    {

    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function purge()
    {

    }

}