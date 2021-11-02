<?php

namespace Modules\Pub\Home\Controllers;

class HomeController extends \App\Controllers\BaseController
{
    public function index()
    {
        return view('\Modules\Pub\Home\Views\v_home');
    }
}