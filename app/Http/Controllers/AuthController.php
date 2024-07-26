<?php
//connect database//
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterUser;
use App\Models\RouterosAPI;

class AuthController extends Controller
{
    private $API;

    public function __construct()
    {
        $this->API = new RouterosAPI();
        $this->API->debug = false;
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {   
        $request->validate([
            'ip' => 'required',
            'user' => 'required',
            'password' => 'required',
        ]);

        $ip = $request->post('ip');
        $username = $request->post('user');
        $password = $request->post('password');

        $routerUser = RouterUser::where('ip', $ip)
            ->where('username', $username)
            ->where('password', $password)
            ->first();

        if ($routerUser) {
            // if ($this->API->connect($ip, $username, $password)) {
                $request->session()->put([
                    'ip' => $ip,
                    'user' => $username,
                    'pass' => $password,
                ]);
                return redirect()->route('dashboard');
            // } else {
            //     return redirect()->route('login')->with('error', 'Failed to connect to the router.');
            // }
        } else {
            return redirect()->route('login')->with('error', 'Invalid credentials.');
        }


    }
}


// connect real time //
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\RouterosAPI;

// class AuthController extends Controller
// {
//     private $API;

//     public function __construct()
//     {
//         $this->API = new RouterosAPI();
//         $this->API->debug = false;
//     }

//     public function index()
//     {
//         return view('auth.login');
//     }

//     public function login(Request $request)
//     {   
//         $request->validate([
//             'ip' => 'required',
//             'user' => 'required',
//         ]);
        
//         $ip = $request->post('ip');
//         $user = $request->post('user');
//         $pass = $request->post('password', '');

//         if ($this->API->connect($ip, $user, $pass)) {
//             $request->session()->put([
//                 'ip' => $ip,
//                 'user' => $user,
//                 'pass' => $pass,
//             ]);
//             return redirect()->route('dashboard');
//         }

//         return redirect()->route('login')->with('error', 'Invalid credentials or unable to connect to the router.');
//     }
// }
