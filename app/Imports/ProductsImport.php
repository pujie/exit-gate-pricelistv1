<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure; // Tambah ini
use Maatwebsite\Excel\Validators\Failure;
class ProductsImport implements ToModel,WithHeadingRow,WithValidation,SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        Log::info('memproses baris',$row);
        return new Product([
           'id'  => $row['id'],
           'code' => $row['code'],
           'name' => $row['name'],
           'price' => $row['price']
        ]);
    }
    public function rules(): array
    {
        return [
            'code'  => 'required',
            'name' => 'required',
        ];
    }

    /**
     * Fungsi ini akan berjalan OTOMATIS jika ada baris yang gagal divalidasi
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error("Import Gagal di Baris " . $failure->row(), [
                'atribut' => $failure->attribute(),
                'errors'  => $failure->errors(),
                'values'  => $failure->values(), // Data asli di baris tersebut
            ]);
        }
    }
}
