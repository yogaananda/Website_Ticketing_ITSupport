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
        'ticket_id',      
        'item_name',
        'description',
        'quantity',
        'estimated_price',
        'link_reference',
        'priority',       
        'status',          
        'admin_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}