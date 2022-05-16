<?php

namespace Modules\Api\Perkuliahan\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Perkuliahan\Models\PerkuliahanModel;

class PerkuliahanController extends BaseController
{

    private $perkuliahanModel;

    public function __construct()
    {
        $this->perkuliahanModel = new PerkuliahanModel();
    }

    public function index()
    {
        $userdata = (object) $this->request->header('User-Data')->getValue();
        $mhsId = $this->perkuliahanModel->getMahasiswaID($userdata->id);
        if (is_null($mhsId)) {
            throw new ApiAccessErrorException(
                message: 'Mahasiswa tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $perkuliahan = $this->perkuliahanModel->getList($mhsId);
        if (empty($perkuliahan)) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan hari ini tidak ada!',
                statusCode: ResponseInterface::HTTP_NOT_FOUND
            );
        }

        return $this->response
            ->setJSON([
                'data'  => $perkuliahan
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function get($id)
    {
        $perkuliahan = $this->perkuliahanModel->getOne($id);
        if (is_null($perkuliahan)) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_NOT_FOUND
            );
        }

        return $this->response
            ->setJSON($perkuliahan)
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function doPresensi()
    {
        if (!$this->validate([
            'qrsecret'  => 'required'
        ], [
            'qrsecret'  => [
                'required'  => 'QR Secret is required in this request!'
            ]
        ]))
            throw new ApiAccessErrorException(
                message: 'Validation Error!',
                statusCode: ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                extras: ['errors'    => $this->validator->getErrors()]
            );

        $qrsecret = $this->request->getVar('qrsecret');
        $userdata = (object) $this->request->header('User-Data')->getValue();
        $dosenQr = $this->perkuliahanModel->getDosenQR($qrsecret);
        if (is_null($dosenQr)) {
            throw new ApiAccessErrorException(
                message: 'QRCode is invalid!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        // check if user registered in this jadwal/kelas
        $mhsId = $this->perkuliahanModel->getMahasiswaID($userdata->id);
        $isRegistered = $this->perkuliahanModel->checkMahasiswaRegisteredToKelas($mhsId, $dosenQr->id_kelas);
        if (!$isRegistered) {
            throw new ApiAccessErrorException(
                message: 'This mahasiswa is not registered to this class.',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        // check if the jadwal is already expired or not
        $nowDate = date('Y-m-d');
        if ($nowDate > $dosenQr->date) {
            $startTimestamp = strtotime($dosenQr->date);
            $endTimestamp = strtotime($nowDate);
            $timeDiff = abs($endTimestamp - $startTimestamp);
            $numberofdays = $timeDiff/(60*60*24); // 1 day in seconds
            $numberofdays = intval($numberofdays);
            throw new ApiAccessErrorException(
                message: 'The jadwal is expired, it\'s ended '.$numberofdays.' days ago!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        
        if ($nowDate < $dosenQr->date) {
            $startTimestamp = strtotime($nowDate);
            $endTimestamp = strtotime($dosenQr->date);
            $timeDiff = abs($endTimestamp - $startTimestamp);
            $numberofdays = $timeDiff/(60*60*24); // 1 day in seconds
            $numberofdays = intval($numberofdays);
            throw new ApiAccessErrorException(
                message: 'It\'s not the time yet, the class will start in '.$numberofdays.' days!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        
        $nowTime = date('H:i');
        if ($nowTime < $dosenQr->begin_time) {
            throw new ApiAccessErrorException(
                message: 'It\'s not the time yet.',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }
        
        if ($nowTime > $dosenQr->end_time) {
            throw new ApiAccessErrorException(
                message: 'Can\'t do presensi, time is up!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $getPresensi = $this->perkuliahanModel->getPresensi($dosenQr->id, $mhsId);
        if (!is_null($getPresensi)) {
            throw new ApiAccessErrorException(
                message: 'Presensi can only do once!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $lateTime = strtotime($nowDate . ' ' . $dosenQr->begin_time . ':00 +15 minute');
        $lateTime = date('H:i', $lateTime);
        $statusPresensi = 1;
        if ($nowTime > $lateTime) {
            $statusPresensi = 2;
        }
        $this->perkuliahanModel->doPresensi([
            'id_dosen_qrcode'   => $dosenQr->id,
            'id_mahasiswa'      => $mhsId,
            'status_presensi'   => $statusPresensi
        ]);

        return $this->response
            ->setJSON([
                'message'   => 'Success doing presensi!',
                'status_presensi'   => $statusPresensi == 1 ? 'ontime' : ($statusPresensi == 2 ? 'late' : 'absent'),
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

}