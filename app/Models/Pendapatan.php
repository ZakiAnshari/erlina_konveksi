<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    protected $fillable = [
        'tanggal',
        'sumber',
        'jumlah',
        'bukti_pendapatan',
    ];

    
}
