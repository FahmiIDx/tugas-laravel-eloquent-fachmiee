<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    // Hubungkan ke nama tabel yang ada di gambar kamu
    protected $table = 'jabatans';

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'jabatan_id', 'id');
    }
}