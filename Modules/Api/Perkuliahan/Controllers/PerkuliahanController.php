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
        $userdata = (object) $this
            ->request
            ->header('User-Data')
            ->getValue();

        $mhsId = $this
            ->perkuliahanModel
            ->getMahasiswaID($userdata->id);

        if (is_null($mhsId)) {
            throw new ApiAccessErrorException(
                message: 'Mahasiswa tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $perkuliahan = $this
            ->perkuliahanModel
            ->getList($mhsId);

        if (empty($perkuliahan)) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan hari ini tidak ada!',
                statusCode: ResponseInterface::HTTP_NOT_FOUND
            );
        }

        return $this
            ->response
            ->setJSON([
                'data'  => $perkuliahan,
                'total' => count($perkuliahan)
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function get($id)
    {
        $perkuliahan = $this
            ->perkuliahanModel
            ->getOne($id);

        if (is_null($perkuliahan)) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_NOT_FOUND
            );
        }

        return $this
            ->response
            ->setJSON($perkuliahan)
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function doPresensi()
    {
        if (!$this->validate([
            'qrsecret'  => 'required',
            'id_jadwal' => 'required'
        ], [
            'qrsecret'  => [
                'required'  => 'QR Secret is required in this request!',
            ],
            'id_jadwal' => [
                'required'  => 'ID Jadwal is required in this request!'
            ]
        ]))
            throw new ApiAccessErrorException(
                message: 'Validation Error!',
                statusCode: ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                extras: ['errors'    => $this->validator->getErrors()]
            );

        $qrsecret   = $this->request->getVar('qrsecret');
        $idJadwal   = $this->request->getVar('id_jadwal');
        $userdata   = (object) $this->request->header('User-Data')->getValue();
        $dosenQr    = $this->perkuliahanModel->getDosenQR($qrsecret, $idJadwal);
        if (is_null($dosenQr)) {
            throw new ApiAccessErrorException(
                message: 'QR tidak valid!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        // check if mhs registered in this jadwal/kelas
        $mhsId          = $this
            ->perkuliahanModel
            ->getMahasiswaID($userdata->id);
        $isRegistered   = $this
            ->perkuliahanModel
            ->checkMahasiswaRegisteredToKelas(
                $mhsId,
                $dosenQr->id_kelas
            );

        if (!$isRegistered) {
            throw new ApiAccessErrorException(
                message: 'Anda tidak terdaftar di kelas ini!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        // check if the jadwal is already expired or not
        $nowDate = date('Y-m-d');
        if ($nowDate > $dosenQr->date) {
            $startTimestamp = strtotime($dosenQr->date);
            $endTimestamp   = strtotime($nowDate);
            $timeDiff       = abs($endTimestamp - $startTimestamp);
            $numberofdays   = $timeDiff / (60 * 60 * 24); // 1 day in seconds
            $numberofdays   = intval($numberofdays);
            throw new ApiAccessErrorException(
                message: 'Jadwal telah kadaluarsa, telah berakhir ' . $numberofdays . ' hari yang lalu!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        if ($nowDate < $dosenQr->date) {
            $startTimestamp = strtotime($nowDate);
            $endTimestamp = strtotime($dosenQr->date);
            $timeDiff = abs($endTimestamp - $startTimestamp);
            $numberofdays = $timeDiff / (60 * 60 * 24); // 1 day in seconds
            $numberofdays = intval($numberofdays);
            throw new ApiAccessErrorException(
                message: 'Jadwal belum dimulai, masih ' . $numberofdays . ' hari lagi!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $nowTime = date('H:i');
        if ($nowTime < $dosenQr->begin_time) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan belum dimulai!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        if ($nowTime > $dosenQr->end_time) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan telah selesai, tidak dapat melakukan presensi!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $getPresensi = $this->perkuliahanModel->getPresensi($dosenQr->id, $mhsId);
        if (!is_null($getPresensi)) {
            throw new ApiAccessErrorException(
                message: 'Anda sudah presensi!',
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

        return $this
            ->response
            ->setJSON([
                'message'   => 'Berhasil melakukan presensi!',
                'status_presensi'   => $statusPresensi == 1 ? 'ontime' : ($statusPresensi == 2 ? 'late' : 'absent'),
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}