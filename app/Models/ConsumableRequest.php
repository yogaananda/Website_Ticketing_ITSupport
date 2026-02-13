<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableRequest extends Model
{
    use HasFactory;

    protected $table = 'consumable_requests'; // Pastikan nama tabel benar

    protected $fillable = [
        'user_id',
        'consumable_id',
        'amount',
        'status',
        'admin_id', // Tambahan sesuai migrasi Anda
        'reason',   // Ganti 'notes' jadi 'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }
    
    // Opsional: Relasi ke Admin (User)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}