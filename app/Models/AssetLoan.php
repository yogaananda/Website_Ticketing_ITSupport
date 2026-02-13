<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLoan extends Model
{
    use HasFactory;

    protected $table = 'asset_loans';
    protected $guarded = ['id'];

    // PENTING: Casting agar tanggal dianggap sebagai object Carbon (bisa diformat)
    protected $casts = [
        'loan_date' => 'datetime',
        'due_date'  => 'datetime',
        'return_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}