<?php

namespace Modules\Admin\Setting\Controllers;

use App\Controllers\BaseController;

class SettingController extends BaseController
{

    public function __construct()
    {
        
    }

    public function index()
    {
        return view('\Modules\Admin\Setting\Views\v_index', [
            'page_title'    => 'Data Presensi',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'setting'     => [
                    'url'       => route_to('setting'),
                    'active'    => true,
                ]
            ]
        ]);
    }

}