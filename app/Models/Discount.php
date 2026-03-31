<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'odoo_product_id', 'type', 'value', 'start_date', 'end_date', 'description', 'flyer_path'
    ];
}
