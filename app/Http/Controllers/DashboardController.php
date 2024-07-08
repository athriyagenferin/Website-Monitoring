<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterosAPI;

class DashboardController extends Controller
{
    private $API;

    public function __construct()
    {
        $this->API = new RouterosAPI();
        $this->API->debug = false;
    }

    public function index()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');

        if (!$ip || !$user || !$pass) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $data = $this->fetchData($ip, $user, $pass);

        return view('dashboard', compact('data'));
    }

    private function fetchData($ip, $user, $pass)
    {
        $data = [];

        if ($this->API->connect($ip, $user, $pass)) {
            $cpu = $this->API->comm('/system/resource/print');
            $uptime = $this->API->comm('/system/resource/print');
            $routerModel = $this->API->comm('/system/routerboard/print');
            $totalPPPoESecret = $this->API->comm('/ppp/secret/print');
            $hotspotActive = $this->API->comm('/ip/hotspot/active/print');
            $freeMemory = $this->API->comm('/system/resource/print');
            $pppoeActive = $this->API->comm('/ppp/active/print');

            $data = [
                'cpuLoad' => !empty($cpu) ? $cpu[0]['cpu-load'] : 'N/A',
                'uptime' => !empty($uptime) ? $uptime[0]['uptime'] : 'N/A',
                'routerBoardName' => !empty($routerModel) ? $routerModel[0]['model'] : 'N/A',
                'totalPPPoESecret' => count($totalPPPoESecret),
                'hotspotActive' => count($hotspotActive),
                'infoModel' => !empty($routerModel) ? $routerModel[0]['model'] : 'N/A',
                'infoOS' => !empty($routerModel) ? $routerModel[0]['current-firmware'] : 'N/A',
                'freeMemoryHdd' => !empty($freeMemory) ? $freeMemory[0]['free-memory'] . ' / ' . $freeMemory[0]['free-hdd-space'] : 'N/A',
                'pppoeActive' => count($pppoeActive),
                'totalUserHotspot' => count($hotspotActive),
            ];
        }

        return $data;
    }

    public function fetchDataAPI()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = session()->get('pass');

        $data = $this->fetchData($ip, $user, $pass);

        if (empty($data)) {
            return response()->json(['error' => 'Connection failed'], 500);
        }

        return response()->json($data);
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\RouterosAPI;


// class DashboardController extends Controller
// {
//     public function index()
//     {
//         $ip = session()->get('ip');
//         $user = session()->get('user');
//         $pass = session()-> get('pass');
//         $API = new RouterosAPI();
//         $API->debug('false');




        
//         if($API->connect($ip, $user, $pass)) {
//             $identitas = $API->comm('/');
//             $routermodel = $API->comm('/system/routerboard/print');
//         } else {
//             return 'Koneksi Gagal';
//         }

        
            
//         $data = [
//             'routerBoardName' => '0',
//             'cpuLoad' => '0',
//             'totalPPPoESecret' => '0',
//             'hotspotActive' => '0',
//             'uptime' => '0',
//             'infoModel' => '0 / 0',
//             'infoOS' => '0',
//             'freeMemoryHdd' => '0 / 0',
//             'pppoeActive' => '0',
//             'totalUserHotspot' => '0',
//         ];

//         if ($API->connect($ip, $user, $pass)) {
//             $cpu = $API->comm('/system/resource/print');
//             $uptime = $API->comm('/system/resource/print');
//             $routermodel = $API->comm('/system/routerboard/print');
//             // Add other necessary API calls here

//             if (!empty($cpu)) {
//                 $data['cpuLoad'] = $cpu[0]['cpu-load'];
//             }

//             if (!empty($uptime)) {
//                 $data['uptime'] = $uptime[0]['uptime'];
//             }

//             if (!empty($routermodel)) {
//                 $data['routerBoardName'] = $routermodel[0]['routerBoardName'] ?? 'N/A';
//                 // Add other necessary data parsing here
//             }

//             // Fetch and parse other data similarly
//         } else {
//             return 'Koneksi Gagal';
//         }

//         return view('dashboard', compact('data'));
//     }


// public function cpu(){

//     $ip = session()-> get('ip');
//     $user = session()-> get('user');
//     $pass = session()-> get('pass');
//     $API = new RouterosAPI();
//     $API->debug = false;

//     if ($API->connect($ip, $user, $pass)) {
//         $cpu = $API->comm('/system/resource/print');
//         if (!empty($cpu)) {
//             $data = [
//                 'cpuLoad' => $cpu[0]['cpu-load'],
//             ];
//         } else {
//             return 'Data CPU tidak ditemukan';
//         }
//     } else {
//         return 'Koneksi Gagal';
//     }

//     return view('realtime.cpu', compact('data'));

// }

// public function uptime()
// {
//     $ip = session()->get('ip');
//     $user = session()->get('user');
//     $pass = session()->get('pass');
//     $API = new RouterosAPI();
//     $API->debug = false;

//     if ($API->connect($ip, $user, $pass)) {
//         $uptime = $API->comm('/system/resource/print');
//         if (!empty($uptime)) {
//             $data = [
//                 'uptime' => $uptime[0]['uptime'],
//             ];
//         } else {
//             return 'Data Uptime tidak ditemukan';
//         }
//     } else {
//         return 'Koneksi Gagal';
//     }

//     return view('realtime.uptime', compact('data'));
// }

// }

        // $data= [
        //     'identitas' => $identitas[0]['name'],
        //     'routermodel' => $routermodel[0]['model'],
        // ];
        
        //dd($identitas);

        // return view('layouts.master');
     
            // Data dummy untuk sementara
            
            