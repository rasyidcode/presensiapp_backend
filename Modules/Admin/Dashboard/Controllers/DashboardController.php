<?php

namespace Modules\Admin\Dashboard\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dashboard\Models\DashboardModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class DashboardController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $dashboardModel;

    public function __construct()
    {
        parent::__construct();

        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        $totalMhs = $this->dashboardModel
            ->builder('mahasiswa')
            ->selectCount('*', 'count')
            ->get()
            ->getRowObject()
            ->count;
        $totalDosen = $this->dashboardModel
            ->builder('dosen')
            ->selectCount('*', 'count')
            ->get()
            ->getRowObject()
            ->count;
        $totalJadwal = $this->dashboardModel
            ->builder('jadwal')
            ->selectCount('*', 'count')
            ->get()
            ->getRowObject()
            ->count;
        $totalMatkul = $this->dashboardModel
            ->builder('matkul')
            ->selectCount('*', 'count')
            ->get()
            ->getRowObject()
            ->count;
        $recentLogs = $this->dashboardModel
            ->builder('activity_logs')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->getResultObject();
        $perkuliahan = $this->dashboardModel
            ->builder('jadwal')
            ->select('
                jadwal.id as id_jadwal,
                SUBSTR(jadwal.begin_time, 1, 5) as begin_time,
                SUBSTR(jadwal.end_time, 1, 5) as end_time,
                matkul.nama as matkul,
                dosen.nama_lengkap as dosen
            ')
            ->join('kelas', 'jadwal.id_kelas = kelas.id', 'left')
            ->join('dosen', 'kelas.id_dosen = dosen.id', 'left')
            ->join('matkul', 'kelas.id_matkul = matkul.id', 'left')
            ->orderBy('jadwal.id', 'desc')
            ->limit(10)
            ->get()
            ->getResultObject();
        
        return $this->renderView('v_welcome', [
            'page_title'    => 'Welcome',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => true,
                ]
            ],
            'totalMahasiswa'   => $totalMhs,
            'totalDosen'       => $totalDosen,
            'totalPerkuliahan' => $totalJadwal,
            'totalMatkul'      => $totalMatkul,
            'perkuliahan'       => $perkuliahan,
            'recentLogs'       => $recentLogs,
        ]);
    }

    public function error404()
    {
        return $this->renderView('v_404', [
            'page_title'    => 'Not Found',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'not-found'     => [
                    'url'       => route_to('admin.error-404'),
                    'active'    => true,
                ],
            ]
        ]);
    }

    public function logout()
    {
        session()->destroy();
        
        return $this->response
            ->setJSON([
                'success' => true,
                'message' => 'User logged out!'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}