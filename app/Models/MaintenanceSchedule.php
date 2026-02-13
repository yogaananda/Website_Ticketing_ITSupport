<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel migrasi Anda
    protected $table = 'maintenance_schedules';

    protected $fillable = [
        'asset_id',
        'technician_id',
        'scheduled_date',
        'completion_date',
        'status',
        'report',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completion_date' => 'date',
    ];

    // Relasi ke Aset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    // Relasi ke Teknisi (User)
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}