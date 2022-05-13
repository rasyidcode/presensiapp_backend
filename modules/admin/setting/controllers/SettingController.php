<?php

namespace Modules\Admin\Setting\Controllers;

use Modules\Shared\Core\Controllers\BaseWebController;

class SettingController extends BaseWebController
{

    protected $viewPath = __DIR__;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->renderView('v_index', [
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