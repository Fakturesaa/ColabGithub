<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembina extends Model
{
    protected $table = 'pembina';

    protected $fillable = ['nama', 'no_hp', 'keahlian'];

    public function ekskul(): HasMany
    {
        return $this->hasMany(Ekskul::class, 'pembina_id');
    }
}
