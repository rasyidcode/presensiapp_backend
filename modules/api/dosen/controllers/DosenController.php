<?php

namespace Modules\Api\Dosen\Controllers;

use App\Controllers\BaseController;
use Modules\Api\Dosen\Models\DosenModel;

class DosenController extends BaseController
{

    /**
     * @var Modules\Api\Dosen\Models\DosenModel
     */
    private $dosenModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
    }

    public function postQr()
    {
        // TODO: post a new qr code
        // TODO: create new dosen_qrcode
    }

}