<?php

namespace Modules\Api\Dosen\Controllers;

use App\Controllers\BaseController;
use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Modules\Api\Dosen\Models\DosenModel;

class DosenController extends BaseController
{

    private $dosenModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
    }

    public function listPerkuliahan()
    {
        $userdata = (object) $this
            ->request
            ->header('User-Data')
            ->getValue();

        $idDosen = $this
            ->dosenModel
            ->getDosenID((int) $userdata->id);

        if (is_null($idDosen)) {
            throw new ApiAccessErrorException(
                message: 'Dosen tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $perkuliahan = $this
            ->dosenModel
            ->getListPerkuliahan($idDosen);

        return $this
            ->response
            ->setJSON([
                'total' => count($perkuliahan),
                'data'  => $perkuliahan
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function perkuliahan($id)
    {
        $perkuliahan = $this
            ->dosenModel
            ->getPerkuliahan((int) $id);

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

    public function postQr()
    {
        if (!$this->validate([
            'qr_secret'  => 'required|is_unique[dosen_qrcode.qr_secret]',
            'id_jadwal' => 'required|is_unique[dosen_qrcode.id_jadwal]'
        ], [
            'qr_secret'  => [
                'required'  => 'QR Secret is required!',
                'is_unique' => 'QR Secret must be unique!'
            ],
            'id_jadwal' => [
                'required'  => 'ID Jadwal is required!',
                'is_unique' => 'ID Jadwal must be unique!'
            ]
        ]))
            throw new ApiAccessErrorException(
                message: 'Validation Error!',
                statusCode: ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                extras: [
                    'errors'    => $this
                        ->validator
                        ->getErrors()
                ]
            );
        
        $userdata   = (object) $this
            ->request
            ->header('User-Data')
            ->getValue();
        
        $idDosen = $this
            ->dosenModel
            ->getDosenID($userdata->id);
        
        $qrsecret   = $this
            ->request
            ->getVar('qr_secret');
        $idJadwal   = $this
            ->request
            ->getVar('id_jadwal');

        $jadwal = $this
            ->dosenModel
            ->getJadwal($idJadwal, $idDosen);
        
        if (is_null($jadwal)) {
            throw new ApiAccessErrorException(
                message: 'Jadwal tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST);
        }
        
        // check if the jadwal is already expired or not
        $nowDate = date('Y-m-d');
        if ($nowDate > $jadwal->date) {
            $startTimestamp = strtotime($jadwal->date);
            $endTimestamp   = strtotime($nowDate);
            $timeDiff       = abs($endTimestamp - $startTimestamp);
            $numberofdays   = $timeDiff / (60 * 60 * 24); // 1 day in seconds
            $numberofdays   = intval($numberofdays);
            throw new ApiAccessErrorException(
                message: 'Perkuliahan telah kadaluarsa, telah berakhir ' . $numberofdays . ' hari yang lalu!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        if ($nowDate < $jadwal->date) {
            $startTimestamp = strtotime($nowDate);
            $endTimestamp = strtotime($jadwal->date);
            $timeDiff = abs($endTimestamp - $startTimestamp);
            $numberofdays = $timeDiff / (60 * 60 * 24); // 1 day in seconds
            $numberofdays = intval($numberofdays);
            throw new ApiAccessErrorException(
                message: 'Perkuliahan tidak dapat dimulai hari ini!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $nowTime = date('H:i');
        if ($nowTime < $jadwal->begin_time) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan tidak dapat dimulai saat ini!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        if ($nowTime > $jadwal->end_time) {
            throw new ApiAccessErrorException(
                message: 'Perkuliahan telah selesai, tidak dapat membuat QR!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $qrId = $this
            ->dosenModel
            ->postQR([
                'id_jadwal' => $idJadwal,
                'qr_secret' => $qrsecret
            ]);

        $mhsIds = $this
            ->dosenModel
            ->getListMahasiswaIDs($idJadwal);
        
        $presensiData = [];
        foreach($mhsIds as $mhsId) {
            $presensiData[] = [
                'id_dosen_qrcode'   => $qrId,
                'id_mahasiswa'      => $mhsId,
                'status_presensi'   => 0, // 0 => belum presensi, 1 => tepat waktu, 2 => tidak hadir
            ];
        }

        $this
            ->dosenModel
            ->initPresensiMahasiswa($presensiData);

        return $this
            ->response
            ->setJSON([
                'message'   => 'QR Berhasil Dibuat!',
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function submitPerkuliahan()
    {
        if (!$this->validate([
            'id_jadwal' => 'required'
        ], [
            'id_jadwal' => ['required'  => 'ID Jadwal is required!']
        ]))
            throw new ApiAccessErrorException(
                message: 'Validation Error!',
                statusCode: ResponseInterface::HTTP_UNPROCESSABLE_ENTITY,
                extras: [
                    'errors'    => $this
                        ->validator
                        ->getErrors()
                ]
            );
        
        $userdata   = (object) $this
            ->request
            ->header('User-Data')
            ->getValue();

        $idDosen = $this
            ->dosenModel
            ->getDosenID($userdata->id);
        $idJadwal   = $this
            ->request
            ->getVar('id_jadwal');
        
        $jadwal = $this
            ->dosenModel
            ->getJadwal($idJadwal, $idDosen);
        
        if (is_null($jadwal)) {
            throw new ApiAccessErrorException(
                message: 'Jadwal tidak ditemukan!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST);
        }

        // todo: batas waktu submit perkuliahan adalah, end_time + 30 menit
        $nowTime = date('H:i');
        $endTime = date('H:i', strtotime($jadwal->end_time . '+30 minute'));

        if ($nowTime > $endTime) {
            throw new ApiAccessErrorException(
                message: 'Batas waktu submit perkuliahan telah berakhir, tidak dapat melakukan submit!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $mhsIds = $this
            ->dosenModel
            ->getListMahasiswaIDs($idJadwal);
        $this->dosenModel->db->transBegin();
        try {
            foreach($mhsIds as $mhsId) {
                $presensiMhs = $this->dosenModel->getPresensi((int) $mhsId, (int) $idJadwal);
                if ($presensiMhs->status_presensi <> 1) {
                    $this->dosenModel->updatePresensi((int) $mhsId, (int) $presensiMhs->id_dosen_qrcode, 2);
                }
            }

            $this->dosenModel->db->transCommit();
        } catch (Exception $e) {
            $this->dosenModel->db->transRollback();
        }
        
        return $this
            ->response
            ->setJSON([
                'message'   => 'Perkuliahan berhasil disubmit!',
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
