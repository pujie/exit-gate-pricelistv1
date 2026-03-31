<?php

namespace App\Listeners;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
class AuthenticateOdooAfterLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle2(Login $event)
    {
        // Data login Odoo (sesuaikan dengan database Anda)
        $url = env("odoo_host");
        $params = [
            'jsonrpc' => '2.0',
            'params' => [
                "db" => env("odoo_db"),
                "login" => env("odoo_user"),
                "password" => env("odoo_pass"), // Mengambil password dari request login saat ini
            ]
        ];

        // Kirim request ke Odoo
        $response = Http::post($url, $params);

        if ($response->successful()) {
            $result = $response->json();
            
            // Ambil session_id dari body atau cookie response
            // Odoo biasanya mengembalikan session_id di dalam result atau via header set-cookie
            $odooSessionId = $result['result']['session_id'] ?? null;

            if ($odooSessionId) {
                // Simpan ke session PHP/Laravel
                Session::put('odoo_session_id', $odooSessionId);
            }
        }
    }
    public function handle(Login $event){
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

}
