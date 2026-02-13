<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $table = 'procurements';

    protected $fillable = [
        'user_id',
        'ticket_id',       // Nullable
        'item_name',
        'description',
        'quantity',
        'estimated_price',
        'link_reference',
        'priority',        // Enum: low, medium, high, critical
        'status',          // Enum: pending, approved, etc
        'admin_note'
    ];

    // Relasi ke User (Pemohon)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tiket (Opsional, jika pembelian terkait tiket servis tertentu)
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}