<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'currency',
        'description',
        'stock',
        'unit',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
