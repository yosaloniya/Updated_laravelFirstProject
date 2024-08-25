<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subsku extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'subskus';
     public $timestamps = true;

    protected $fillable = [
        'product_id',
        'sku',
        'sup_id',
        'qty',
        'status',
        'description',
        'location',
        'date',
        'user_id'
    ];
    
}
