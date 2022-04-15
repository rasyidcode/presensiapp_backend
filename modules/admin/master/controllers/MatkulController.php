<?php

namespace Modules\Admin\Master\Controllers;

use App\Controllers\BaseController;

class MatkulController extends BaseController
{
    public function index()
    {
        return view('\Modules\Admin\Master\Views\Matkul\v_index', [
            'page_title'    => 'Data Mata Kuliah'
        ]);
    }

    public function getData()
    {

    }
}