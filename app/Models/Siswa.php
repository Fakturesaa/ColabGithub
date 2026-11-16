<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = ['nis', 'nama', 'kelas', 'jenis_kelamin'];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'siswa_id');
    }
}
