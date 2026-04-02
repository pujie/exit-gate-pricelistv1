<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount; // Pastikan Model sudah dibuat
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{
    /**
     * Menampilkan daftar diskon yang sudah dibuat
     */
    public function index()
    {
        // Mengambil data diskon lokal dan join manual (atau via API) untuk info produk
        // Di sini kita ambil data dari postgres lokal
        $discounts = Discount::orderBy('created_at', 'desc')->get();
        
        return view('discounts.index', compact('discounts'));
    }

    /**
     * Form tambah diskon (Hanya Manager)
     */
    public function create()
    {
        // Kita ambil daftar produk dari Odoo untuk dipilih di dropdown
        $products = DB::connection('pgsql')
            ->table('products')
        //    ->join('product_template', 'product_product.product_tmpl_id', '=', 'product_template.id')
        //    ->select('product_product.id', 'product_template.name', 'product_product.default_code')
        //    ->where('product_template.active', true)
            ->get();

        return view('discounts.create', compact('products'));
    }

    /**
     * Menyimpan data diskon ke Database Lokal (Postgres)
     */
    public function store(Request $request)
    {
        dd($request);
        // 1. VALIDASI (Di CI3 biasanya pakai $this->form_validation)
        $request->validate([
            //'odoo_product_id' => 'required',
            //'product_id' => 'required',
            //'discount_id' => 'required',
            'name'=>'required',
            'type'            => 'required|in:value,percentage',
            'amount'          => 'required|numeric',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'image_flyer'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'description'     => 'nullable|string',
        ]);

        // 2. HANDLING UPLOAD GAMBAR
        $imagePath = null;
        if ($request->hasFile('image_flyer')) {
            // Menyimpan ke folder storage/app/public/flyers
            $imagePath = $request->file('image_flyer')->store('flyers', 'public');
        }

        // 3. SIMPAN DATA (Eloquent ORM)
        Discount::create([
            //'odoo_product_id' => $request->odoo_product_id,
            //'product_id' => $request->product_id,
            //'discount_id' => $request->discount_id,
            'name' => $request->name,
            'type'            => $request->type,
            'amount'          => $request->amount,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'image_flyer'     => $imagePath,
            'description'     => $request->description,
        ]);

        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil dibuat!');
    }

    /**
     * Menghapus diskon dan filenya
     */
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($discount->image_flyer) {
            Storage::disk('public')->delete($discount->image_flyer);
        }

        $discount->delete();

        return redirect()->back()->with('success', 'Diskon berhasil dihapus.');
    }
    public function clean(){

        DB::table('products')->truncate();

    }
}