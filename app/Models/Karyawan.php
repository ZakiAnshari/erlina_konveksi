<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = [
        'nama',
        'posisi',
        'kontak',
        'umur',
        'alamat',
    ];
}
