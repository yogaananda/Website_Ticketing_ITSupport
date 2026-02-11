<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan nama tabel di database kamu (biasanya 'categories')
    protected $table = 'categories'; 
    
    // Agar bisa diisi
    protected $guarded = []; 
}