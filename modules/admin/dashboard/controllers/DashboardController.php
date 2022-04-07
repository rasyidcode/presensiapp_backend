<?php

namespace Modules\Admin\Dashboard\Controllers;

class DashboardController extends \App\Controllers\BaseController
{
    public function index()
    {
        return view('\Modules\Admin\Dashboard\Views\v_welcome', [
            'page_title'    => 'Welcome'
        ]);
    }
}