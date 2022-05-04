<?php

namespace Modules\Admin\Mahasiswa\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Kelas\Models\KelasModel;

class KelasController extends BaseController
{

    private $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
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
        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item->id."\">{$num}.";
            $row[]  = $item->nama_kelas ?? '-';
            $row[]  = $item->nama_matkul ?? '-';
            $row[]  = $item->dosen_pengajar ?? '-';
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-danger btn-xs\">Hapus</a>
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
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'nim'               => 'required|is_unique[mahasiswa.nim]',
            'nama_lengkap'      => 'required|is_unique[mahasiswa.nama_lengkap]',
            'id_jurusan'        => 'required',
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
            'username'  => $dataPost['nim'],
            'password'  => password_hash('12345', PASSWORD_BCRYPT),
            'level'     => 'mahasiswa',
            'email'     => 'dummy#'.$dataPost['nim']
        ]);

        $lastID = $this->userModel->getLastID();
        $dataPost['id_user'] = $lastID;

        $this->mahasiswaModel->create($dataPost);

        session()->setFlashdata('success', 'Mahasiswa telah ditambahkan!');
        return redirect()->back();
    }
}