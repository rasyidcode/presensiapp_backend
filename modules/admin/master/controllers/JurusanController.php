<?php

namespace Modules\Admin\Master\Controllers;

use App\Controllers\BaseController;

class JurusanController extends BaseController
{
    public function index()
    {
        return view('\Modules\Admin\Master\Views\Jurusan\v_index', [
            'page_title'    => 'Data Jurusan'
        ]);
    }

    public function getData()
    {

    }
}