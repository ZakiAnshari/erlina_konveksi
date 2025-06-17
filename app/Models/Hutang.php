<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    // Field yang bisa diisi
    protected $fillable = [
        'tanggal',
        'nama_pihak',
        'tipe',
        'jumlah',
        'jatuh_tempo',
        'status',
    ];
}
