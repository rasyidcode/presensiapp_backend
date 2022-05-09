<?php

namespace Modules\Admin\Kelas\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dosen\Models\DosenModel;
use Modules\Admin\Kelas\Models\KelasModel;
use Modules\Admin\Master\Models\MatkulModel;

class KelasController extends BaseController
{

    private $kelasModel;
    private $matkulModel;
    private $dosenModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->matkulModel = new MatkulModel();
        $this->dosenModel = new DosenModel();
    }

    public function index()
    {
        return view('\Modules\Admin\Kelas\Views\v_index', [
            'page_title'    => 'Data Kelas',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-kelas'     => [
                    'url'       => route_to('kelas.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->kelasModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach ($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"" . $item->id . "\">{$num}.";
            $row[]  = $item->kelas ?? '-';
            $row[]  = $item->matkul ?? '-';
            $row[]  = $item->dosen ?? '-';
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"" . route_to('kelas.mahasiswa', $item->id) . "\" class=\"btn btn-warning btn-xs mr-2\">List Mahasiswa</a>
                            <a href=\"" . route_to('admin.error-404') . "\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"" . route_to('admin.error-404') . "\" class=\"btn btn-danger btn-xs\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->kelasModel->countData(),
                'recordsFiltered'   => $this->kelasModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        $matkulList = $this->matkulModel->getAvailableMatkul();
        $dosenList = $this->dosenModel->getList();

        return view('\Modules\Admin\Kelas\Views\v_add', [
            'page_title'    => 'Tambah Kelas',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-kelas'     => [
                    'url'       => route_to('kelas.list'),
                    'active'    => false,
                ],
                'tambah-kelas'   => [
                    'url'       => route_to('kelas.add'),
                    'active'    => true,
                ],
            ],
            'matkulList'    => $matkulList,
            'dosenList'     => $dosenList
        ]);
    }

    public function create()
    {
        $rules = [
            'matkul'    => 'required',
            'dosen'     => 'required',
        ];
        $messages = [
            'matkul'    => [
                'required'  => 'Pilih salah satu mata kuliah!',
            ],
            'dosen'     => [
                'required'  => 'Pilih salah satu dosen!'
            ]
        ];
        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $this->kelasModel->create([
            'id_dosen'  => $dataPost['dosen'],
            'id_matkul' => $dataPost['matkul'],
        ]);

        session()->setFlashdata('success', 'Kelas telah ditambahkan!');
        return redirect()->back();
    }

    public function mahasiswa($id)
    {
        $kelas = $this->kelasModel->get((int)$id);
        return view('\Modules\Admin\Kelas\Views\v_list_mahasiswa', [
            'page_title'    => 'Data Kelas',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-kelas'     => [
                    'url'       => route_to('kelas.list'),
                    'active'    => false,
                ],
                'data-kelas-mahasiswa'     => [
                    'url'       => route_to('kelas.list-mahasiswa'),
                    'active'    => true,
                ],
            ],
            'kelasInfo'     => $kelas
        ]);
    }

    public function mahasiswaGetData($id)
    {
        $postData   = $this->request->getPost();
        $data       = $this->kelasModel->getMahasiswaData((int) $id, $postData);
        $num        = $postData['start'];

        $resData = [];
        foreach ($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "{$num}.";
            $row[]  = $item->nim ?? '-';
            $row[]  = $item->nama_lengkap ?? '-';
            $row[]  = $item->jurusan ?? '-';
            $row[]  = $item->jenis_kelamin ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"" . route_to('admin.error-404') . "\" class=\"btn btn-danger btn-xs\">Hapus Dari Kelas</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->kelasModel->mahasiswaCountData($id),
                'recordsFiltered'   => $this->kelasModel->mahasiswaCountFilteredData($id, $postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
