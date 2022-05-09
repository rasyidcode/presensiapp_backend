<?php

namespace Modules\Api\Jadwal\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Jadwal\Models\JadwalModel;

class JadwalController extends BaseController
{

    private $jadwalModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
    }

    public function index()
    {
        $userdata = (object) $this->request->header('User-Data')->getValue();
        $mhsId = $this->jadwalModel->getMahasiswaID($userdata->id);
        $jadwal = $this->jadwalModel->getJadwal($mhsId);
        
        if (is_null($jadwal)) {
            throw new ApiAccessErrorException(
                message: 'Jadwal tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_NOT_FOUND
            );
        }

        return $this->response
            ->setJSON([
                'data'  => $jadwal
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

}