<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Returns extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'returns';

    protected $fillable = [
        'm_sku',
        's_sku',
        'location',
        'size',
        'date',
        'customer_id',
        'user_id',
    ];
}
