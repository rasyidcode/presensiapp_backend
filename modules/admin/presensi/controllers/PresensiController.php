<?php

namespace Modules\Admin\Presensi\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Kelas\Models\KelasModel;
use Modules\Admin\Presensi\Models\PresensiModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class PresensiController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $presensiModel;
    private $kelasModel;

    public function __construct()
    {
        parent::__construct();

        $this->presensiModel = new PresensiModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $kelasList = $this->kelasModel->getList();
        return $this->renderView('v_index', [
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
            ],
            'kelasList' => $kelasList
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->presensiModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach ($data as $item) {
            $num++;

            $row    = [];
            $row[]  = $num . '.';
            $row[]  = $item->nama_lengkap ?? '-';
            $statusPresensi = '';
            if ($item->status_presensi == 0) {
                $statusPresensi = '<span class="badge badge-danger">Tidak hadir</span>';
            } else if ($item->status_presensi == 1) {
                $statusPresensi = '<span class="badge badge-success">Hadir</span>';
            } else if ($item->status_presensi == 2) {
                $statusPresensi = '<span class="badge badge-warning">Terlambat</span>';
            }
            $row[]  = $statusPresensi;
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"" . route_to('admin.error-404') . "\" class=\"btn btn-info btn-xs mr-2\">Detail</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->presensiModel->countData($postData),
                'recordsFiltered'   => $this->presensiModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
