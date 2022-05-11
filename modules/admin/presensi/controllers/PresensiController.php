<?php

namespace Modules\Admin\Presensi\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Presensi\Models\PresensiModel;

class PresensiController extends BaseController
{

    private $presensiModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiModel();
    }

    public function index()
    {
        return view('\Modules\Admin\Presensi\Views\v_index', [
            'page_title'    => 'Data Presensi',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-presensi'     => [
                    'url'       => route_to('presensi.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

}