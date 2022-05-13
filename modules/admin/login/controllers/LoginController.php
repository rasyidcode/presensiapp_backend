<?php

namespace Modules\Admin\Login\Controllers;

use Modules\Admin\Login\Models\LoginModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class LoginController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $loginModel;

    public function __construct()
    {
        parent::__construct();
        
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        return $this->renderView('v_login');
    }

    public function login()
    {
        $rules = ['username'  => 'required','password'  => 'required'];
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Username atau password harus di isi!');
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $userdata = $this->loginModel->getUser($dataPost['username']);
        if (is_null($userdata)) {
            session()->setFlashdata('error', 'Username atau password salah!');
            return redirect()->back();
        }

        if (!password_verify($dataPost['password'], $userdata['password'])) {
            session()->setFlashdata('error', 'Username atau password salah!');
            return redirect()->back();
        }

        session()->set([
            'logged_in' => true,
            'username'  => $userdata['username'],
            'level'     => $userdata['level']
        ]);
        return redirect()->to(base_url('admin'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->route('login');
    }
}