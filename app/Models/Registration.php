<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_type',
        'total_amount',
        'payment_status',
        'ticket_code'
    ];

    protected $casts = [
        'payment_status' => 'string', // Pendiente, Completado, Fallado
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }


}
