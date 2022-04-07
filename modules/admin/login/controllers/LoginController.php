<?php

namespace Modules\Admin\Login\Controllers;

class LoginController extends \App\Controllers\BaseController
{
    public function index()
    {
        return view('\Modules\Admin\Login\Views\v_login');
    }
}