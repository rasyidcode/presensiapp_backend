<?php

namespace Modules\Admin\Login\Controllers;

use Modules\Admin\Login\Models\LoginModel;

class LoginController extends \App\Controllers\BaseController
{

    private $login_model;

    public function __construct()
    {
        $this->login_model = new LoginModel();
    }

    public function index()
    {
        return view('\Modules\Admin\Login\Views\v_login');
    }

    public function login()
    {
        $rules = ['username'  => 'required','password'  => 'required'];
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Username atau password harus di isi!');
            return redirect()->back();
        }

        $data_post = $this->request->getPost();
        $userdata = $this->login_model->getUser($data_post['username']);
        if (is_null($userdata)) {
            session()->setFlashdata('error', 'Username atau password salah!');
            return redirect()->back();
        }

        if (!password_verify($data_post['password'], $userdata['password'])) {
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