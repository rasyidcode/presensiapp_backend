<?php

namespace Modules\Admin\Jadwal\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dosen\Models\DosenModel;
use Modules\Admin\Jadwal\Models\JadwalModel;
use Modules\Admin\Master\Models\MatkulModel;

class JadwalController extends BaseController
{

    private $jadwalModel;
    private $matkulModel;
    private $dosenModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->matkulModel = new MatkulModel();
        $this->dosenModel = new DosenModel();
    }

    public function index()
    {
        $matkulList = $this->matkulModel->getAll();
        $dosenList = $this->dosenModel->getList();
        return view('\Modules\Admin\Jadwal\Views\v_index', [
            'page_title'    => 'Data Jadwal',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-jadwal'     => [
                    'url'       => route_to('jadwal.list'),
                    'active'    => true,
                ]
            ],
            'matkulList'    => $matkulList,
            'dosenList'    => $dosenList,
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->jadwalModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach ($data as $item) {
            $num++;

            $row    = [];
            $row[]  = $num . '.';
            $row[]  = $item->date ?? '-';
            $row[]  = $item->begin_time ?? '-';
            $row[]  = $item->end_time ?? '-';
            $row[]  = $item->matkul ?? '-';
            $row[]  = $item->dosen ?? '-';
            $row[]  = '0';
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"" . route_to('admin.error-404') . "\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"" . route_to('admin.error-404') . "\" class=\"btn btn-danger btn-xs\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->jadwalModel->countData($postData),
                'recordsFiltered'   => $this->jadwalModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        return view('\Modules\Admin\Dosen\Views\v_add', [
            'page_title'    => 'Tambah Dosen',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-dosen'     => [
                    'url'       => route_to('dosen.list'),
                    'active'    => false,
                ],
                'tambah-dosen'   => [
                    'url'       => route_to('dosen.add'),
                    'active'    => true,
                ],
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'nip'               => 'required|is_unique[dosen.nip]',
            'nama_lengkap'      => 'required|is_unique[dosen.nama_lengkap]',
            'tahun_masuk'       => 'required',
            'jenis_kelamin'     => 'required',
            'alamat'            => 'required',
        ];
        // todo: add custom error messages
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $this->userModel->create([
            'username'  => $dataPost['nip'],
            'password'  => password_hash('12345', PASSWORD_BCRYPT),
            'level'     => 'dosen',
            'email'     => 'dummy#' . $dataPost['nip']
        ]);

        $lastID = $this->userModel->getLastID();
        $dataPost['id_user'] = $lastID;

        $this->dosenModel->create($dataPost);

        session()->setFlashdata('success', 'Dosen telah ditambahkan!');
        return redirect()->back();
    }
}
