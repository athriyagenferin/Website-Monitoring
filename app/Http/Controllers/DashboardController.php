<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterosAPI;


class DashboardController extends Controller
{
    public function index()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $pass = '123';
        $API = new RouterosAPI();
        $API->debug('false');

        if($API->connect($ip, $user, $pass)) {
            $identitas = $API->comm('/system/identity/print');
            $routermodel = $API->comm('/system/routerboard/print');
        } else {
            return 'Koneksi Gagal';
        }

        $data= [
            'identitas' => $identitas[0]['name'],
            'routermodel' => $routermodel[0]['model'],
        ];
        
        //dd($identitas);

        return view('dashboard', $data);
    }
}
