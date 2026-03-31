<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ProductsImport;
use App\Models\Discount;


use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    function gets(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => env("odoo_host").'api/product.product?query={id%2Ccode%2Cname%2Cprice}',
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
        $jsonoutput = response()->json(['data' => $response]);
        $obj = json_decode($jsonoutput);
        $data_array = json_decode($response,true);
        return response()->json(['data' => $data_array["result"]]);
    }

    function index(){
        return view('product');
    }
    function getsession(){
        echo session('odoo_session_id');
    }
    public function import(Request $request) 
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        Excel::import(new ProductsImport, $request->file('file'));
        return response()->json(['message' => 'Success']);
    }
    public function importx(Request $request) 
    {
        $file = $request->file('file');
        
        // Test: Coba ambil data mentahnya dulu tanpa masuk ke Database
        $data = \Excel::toArray(new ProductsImport, $file);
        
        // Ini akan menampilkan isi array dari Excel di console/network tab browser
        return response()->json([
            'debug_data' => $data,
            'message' => 'Cek tab Network di Inspect Element untuk melihat data ini'
        ]);
    }
    public function shodiscount()
    {
        // 1. Ambil data produk dari koneksi database Odoo
        $odooProducts = DB::connection('pgsql')
            ->table('product_product')
            ->join('product_template', 'product_product.product_tmpl_id', '=', 'product_template.id')
            ->select(
                'product_product.id as odoo_id',
                'product_template.name',
                'product_template.list_price as base_price',
                'product_product.default_code as code'
            )
            ->where('product_template.active', true)
            ->get();

        // 2. Ambil diskon yang sedang aktif dari DB Lokal (Postgres)
        $activeDiscounts = Discount::where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get()
            ->keyBy('odoo_product_id');

        // 3. Gabungkan data (Mapping)
        $pricelist = $odooProducts->map(function ($product) use ($activeDiscounts) {
            $discount = $activeDiscounts->get($product->odoo_id);
            
            $finalPrice = $product->base_price;
            if ($discount) {
                if ($discount->type === 'percentage') {
                    $finalPrice -= ($product->base_price * ($discount->value / 100));
                } else {
                    $finalPrice -= $discount->value;
                }
            }

            return [
                'id' => $product->odoo_id,
                'code' => $product->code,
                'name' => $product->name,
                'base_price' => $product->base_price,
                'final_price' => max($finalPrice, 0),
                'discount' => $discount
            ];
        });

        return view('products.index', compact('pricelist'));
    }
    public function showdiscount()
    {
        $product = DB::connection('pgsql')
            ->table('products')
            ->select(
                'id',
                'name',
                'price',
                'code'
            )
            ->get();
        $pricelist = '{"data":'.json_encode($product).'}';
        return $pricelist;
    }
    public function show(){
        return view('products.index');
    }
}
