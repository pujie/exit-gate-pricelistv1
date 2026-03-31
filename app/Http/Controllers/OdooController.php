<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
//use Illuminate\Http\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OdooController extends Controller
{
    public function auth(){
        echo "<br /><br /><h1>".session('odoo_session_id')."</h1><br /><br />";
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => env("odoo_host").'auth/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "params" : {
            "db" => env("odoo_db"),
            "login" => env("odoo_user"),
            "password" => env("odoo_pass")
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: session_id='.session('odoo_session_id')
        ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        $out = json_decode($response,true);
        //$result = $out["result"];
        //print_r($out["result"]);
        //echo $result["session_id"];
        print_r($out);
    }
    public function kukis(){
        $url = env("odoo_host")."auth/";
        $params = [
            "jsonrpc" => "2.0",
            "params" => [
                "db" => env("odoo_db"),
                "login" => env("odoo_user"),
                "password" => env("odoo_pass")
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // AKTIFKAN INI UNTUK MENDAPATKAN HEADER
        curl_setopt($ch, CURLOPT_HEADER, true); 

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        curl_close($ch);


        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);



        $header = substr($response, 0, $header_size);
        $request = new Request();
        // Gunakan Regex untuk mencari session_id di dalam header Set-Cookie
        if (preg_match('/session_id=([^;]+)/', $header, $matches)) {
            $session_id = $matches[1];
            $response = new Response('oto');
            $response->WithCookie(cookie('session_id',$session_id,1));
            //echo "Session ID dari Cookie: " . $session_id;

            session(['odoo_session_id' => $session_id]);

            //echo "<br />Ini ".session('odoo_session_id');

            return view('product');
        }

    }

    public function dataodoo(){
        $url = env("odoo_host")."";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.'api/product.product?query={id%2Ccode%2Cname%2Cprice}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: session_id='.session('odoo_session_id')
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

}
