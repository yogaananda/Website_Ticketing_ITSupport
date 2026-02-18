<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableRequest extends Model
{
    use HasFactory;

    protected $table = 'consumable_requests';

    protected $fillable = [
        'user_id',
        'consumable_id',
        'amount',
        'status',
        'admin_id', 
        'reason',   
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}