<?php
// connect database //
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

        if (empty($data)) {
            return redirect()->route('login')->with('error', 'Failed to connect to the router.');
        }

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



// connect real time //
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\RouterosAPI;

// class DashboardController extends Controller
// {
//     private $API;

//     public function __construct()
//     {
//         $this->API = new RouterosAPI();
//         $this->API->debug = false;
//     }

//     public function index()
//     {
//         $ip = session()->get('ip');
//         $user = session()->get('user');
//         $pass = session()->get('pass');

//         if (!$ip || !$user) {
//             return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
//         }

//         $data = $this->fetchData($ip, $user, $pass);

//         if (empty($data)) {
//             return redirect()->route('login')->with('error', 'Failed to connect to the router.');
//         }

//         return view('dashboard', compact('data'));
//     }

//     private function fetchData($ip, $user, $pass)
//     {
//         $data = [];

//         if ($this->API->connect($ip, $user, $pass)) {
//             $cpu = $this->API->comm('/system/resource/print');
//             $uptime = $this->API->comm('/system/resource/print');
//             $routerModel = $this->API->comm('/system/routerboard/print');
//             $totalPPPoESecret = $this->API->comm('/ppp/secret/print');
//             $hotspotActive = $this->API->comm('/ip/hotspot/active/print');
//             $freeMemory = $this->API->comm('/system/resource/print');
//             $pppoeActive = $this->API->comm('/ppp/active/print');

//             $data = [
//                 'cpuLoad' => !empty($cpu) ? $cpu[0]['cpu-load'] : 'N/A',
//                 'uptime' => !empty($uptime) ? $uptime[0]['uptime'] : 'N/A',
//                 'routerBoardName' => !empty($routerModel) ? $routerModel[0]['model'] : 'N/A',
//                 'totalPPPoESecret' => count($totalPPPoESecret),
//                 'hotspotActive' => count($hotspotActive),
//                 'infoModel' => !empty($routerModel) ? $routerModel[0]['model'] : 'N/A',
//                 'infoOS' => !empty($routerModel) ? $routerModel[0]['current-firmware'] : 'N/A',
//                 'freeMemoryHdd' => !empty($freeMemory) ? $freeMemory[0]['free-memory'] . ' / ' . $freeMemory[0]['free-hdd-space'] : 'N/A',
//                 'pppoeActive' => count($pppoeActive),
//                 'totalUserHotspot' => count($hotspotActive),
//             ];
//         }

//         return $data;
//     }

//     public function fetchDataAPI()
//     {
//         $ip = session()->get('ip');
//         $user = session()->get('user');
//         $pass = session()->get('pass');

//         $data = $this->fetchData($ip, $user, $pass);

//         if (empty($data)) {
//             return response()->json(['error' => 'Connection failed'], 500);
//         }

//         return response()->json($data);
//     }
// }





// data dummy //

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\RouterosAPI; // Pastikan namespace model RouterosAPI sudah benar

// class DashboardController extends Controller
// {
//     private $API;
//     private $isSimulation = true; // Setel ke true untuk mode simulasi

//     public function __construct()
//     {
//         $this->API = new RouterosAPI();
//         $this->API->debug = false;
//     }

//     public function index()
//     {
//         if (!$this->isSimulation) {
//             $ip = session()->get('ip');
//             $user = session()->get('user');
//             $pass = session()->get('pass');

//             if (!$ip || !$user || !$pass) {
//                 return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
//             }
//         }

//         $data = $this->fetchData($ip ?? null, $user ?? null, $pass ?? null);

//         return view('dashboard', compact('data'));
//     }

//     private function fetchData($ip, $user, $pass)
//     {
//         $data = [];

//         if (!$this->isSimulation && $this->API->connect($ip, $user, $pass)) {
//             // Implementasi ambil data dari router
//             $cpu = $this->API->comm('/system/resource/print');
//             $uptime = $this->API->comm('/system/resource/print');
//             $routerModel = $this->API->comm('/system/routerboard/print');
//             $totalPPPoESecret = $this->API->comm('/ppp/secret/print');
//             $hotspotActive = $this->API->comm('/ip/hotspot/active/print');
//             $freeMemory = $this->API->comm('/system/resource/print');
//             $pppoeActive = $this->API->comm('/ppp/active/print');

//             $data = [
//                 'cpuLoad' => !empty($cpu) ? $cpu[0]['cpu-load'] : 'N/A',
//                 'uptime' => !empty($uptime) ? $uptime[0]['uptime'] : 'N/A',
//                 'routerBoardName' => !empty($routerModel) ? $routerModel[0]['model'] : 'N/A',
//                 'totalPPPoESecret' => count($totalPPPoESecret),
//                 'hotspotActive' => count($hotspotActive),
//                 'infoModel' => !empty($routerModel) ? $routerModel[0]['model'] : 'N/A',
//                 'infoOS' => !empty($routerModel) ? $routerModel[0]['current-firmware'] : 'N/A',
//                 'freeMemoryHdd' => !empty($freeMemory) ? $freeMemory[0]['free-memory'] . ' / ' . $freeMemory[0]['free-hdd-space'] : 'N/A',
//                 'pppoeActive' => count($pppoeActive),
//                 'totalUserHotspot' => count($hotspotActive),
//             ];
//         } else {
//             // Data simulasi untuk mode pengembangan
//             $data = [
//                 'cpuLoad' => '50%',
//                 'uptime' => '12 days',
//                 'routerBoardName' => 'Simulated Router',
//                 'totalPPPoESecret' => 10,
//                 'hotspotActive' => 5,
//                 'infoModel' => 'Simulated Model',
//                 'infoOS' => 'Simulated OS',
//                 'freeMemoryHdd' => '100 MB / 1 GB',
//                 'pppoeActive' => 3,
//                 'totalUserHotspot' => 5,
//             ];
//         }

//         return $data;
//     }
// }
//     public function fetchDataAPI()
//     {
//         if (!$this->isSimulation) {
//             $ip = session()->get('ip');
//             $user = session()->get('user');
//             $pass = session()->get('pass');

//             $data = $this->fetchData($ip, $user, $pass);

//             if (empty($data)) {
//                 return response()->json(['error' => 'Connection failed'], 500);
//             }
//         } else {
//             $data = [
//                 'cpuLoad' => '50%',
//                 'uptime' => '12 days',
//                 'routerBoardName' => 'Simulated Router',
//                 'totalPPPoESecret' => 10,
//                 'hotspotActive' => 5,
//                 'infoModel' => 'Simulated Model',
//                 'infoOS' => 'Simulated OS',
//                 'freeMemoryHdd' => '100 MB / 1 GB',
//                 'pppoeActive' => 3,
//                 'totalUserHotspot' => 5,
//             ];
//         }

//         return response()->json($data);
//     }
// }
