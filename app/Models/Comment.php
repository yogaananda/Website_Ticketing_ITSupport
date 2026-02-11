<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Nama tabel di database (Opsional jika nama tabelnya 'comments')
    protected $table = 'ticket_comments'; 

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    /**
     * Relasi ke Tiket
     * (Komentar ini milik tiket siapa?)
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relasi ke User
     * (Siapa yang menulis komentar/log ini? Bisa User atau IT Support)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}