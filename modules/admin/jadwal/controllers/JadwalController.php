<?php

namespace Modules\Admin\Jadwal\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dosen\Models\DosenModel;
use Modules\Admin\Jadwal\Models\JadwalModel;
use Modules\Admin\Kelas\Models\KelasModel;
use Modules\Admin\Master\Models\MatkulModel;

class JadwalController extends BaseController
{

    private $jadwalModel;
    private $matkulModel;
    private $dosenModel;
    private $kelasModel;

    public function __construct()
    {
        $this->jadwalModel  = new JadwalModel();
        $this->matkulModel  = new MatkulModel();
        $this->dosenModel   = new DosenModel();
        $this->kelasModel   = new KelasModel();
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
            $row[]  = $item->mahasiswa_total ?? '0';
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
        $kelasList = $this->kelasModel->getList();
        return view('\Modules\Admin\Jadwal\Views\v_add', [
            'page_title'    => 'Tambah Jadwal',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-jadwal'     => [
                    'url'       => route_to('jadwal.list'),
                    'active'    => false,
                ],
                'tambah-jadwal'   => [
                    'url'       => route_to('jadwal.add'),
                    'active'    => true,
                ],
            ],
            'kelasList' => $kelasList
        ]);
    }

    public function create()
    {
        // todo: endTime cannot be smaller than beginTime
        $rules = [
            'kelas'         => 'required',
            'tanggal'       => 'required',
            'beginTime'     => 'required',
            'endTime'       => 'required',
        ];

        $messages = [
            'kelas' => [
                'required'  => 'Kelas tidak boleh kosong!'
            ],
            'tanggal' => [
                'required'  => 'Tanggal tidak boleh kosong!'
            ],
            'beginTime' => [
                'required'  => 'Jam Mulai tidak boleh kosong!'
            ],
            'endTime' => [
                'required'  => 'Jam Selesai tidak boleh kosong!'
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $this->jadwalModel->create([
            'id_kelas'      => $dataPost['kelas'],
            'date'          => $dataPost['tanggal'],
            'begin_time'    => $dataPost['beginTime'],
            'end_time'      => $dataPost['endTime']
        ]);

        session()->setFlashdata('success', 'Jadwal telah ditambahkan!');
        return redirect()->back();
    }
}
