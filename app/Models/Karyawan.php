<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    // Hubungkan ke nama tabel yang ada di gambar kamu
    protected $table = 'karyawans'; 

    public function jabatan()
    {
        // Parameter kedua adalah foreign key, ketiga adalah primary key tabel jabatan
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }
}