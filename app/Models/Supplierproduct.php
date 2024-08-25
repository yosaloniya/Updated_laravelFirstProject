<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplierproduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplierproducts';

    protected $fillable = [
        'sku',
        'sub_sku',
        'product_id',
        'sup_id',
        'qty',
        'date',
        'user_id',
        'description',
    ];
}
