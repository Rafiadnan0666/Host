<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesan_id', 'pesan_id', 'waktu', 'cost', 'approve', 'batas', 'location', 'user_id'
    ];

    public function pemesan()
    {
        return $this->belongsTo(User::class, 'pemesan_id');
    }

    public function pesan()
    {
        return $this->belongsTo(User::class, 'pesan_id');
    }

    
}

