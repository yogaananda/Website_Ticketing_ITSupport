<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'serial_number',
        'condition',
        'status',
        'user_id',
        'purchase_date',
        'image_path',
        'location',
    ];

    /**
     * Relasi ke User (Pemegang Aset)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}