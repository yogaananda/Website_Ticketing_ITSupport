<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

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

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}