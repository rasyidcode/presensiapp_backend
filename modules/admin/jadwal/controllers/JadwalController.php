<?php

namespace Modules\Admin\Jadwal\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dosen\Models\DosenModel;
use Modules\Admin\Users\Models\UserModel;

class JadwalController extends BaseController
{

    private $dosenModel;
    private $userModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('\Modules\Admin\Dosen\Views\v_index', [
            'page_title'    => 'Data Dosen',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-dosen'     => [
                    'url'       => route_to('dosen.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->dosenModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item->id."\">{$num}.";
            $row[]  = $item->nip ?? '-';
            $row[]  = $item->nama_lengkap ?? '-';
            $row[]  = $item->tahun_masuk ?? '-';
            $row[]  = $item->jenis_kelamin ?? '-';
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
                'recordsTotal'      => $this->dosenModel->countData(),
                'recordsFiltered'   => $this->dosenModel->countFilteredData($postData),
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
            'email'     => 'dummy#'.$dataPost['nip']
        ]);

        $lastID = $this->userModel->getLastID();
        $dataPost['id_user'] = $lastID;

        $this->dosenModel->create($dataPost);

        session()->setFlashdata('success', 'Dosen telah ditambahkan!');
        return redirect()->back();
    }
}