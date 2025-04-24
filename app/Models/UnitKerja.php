<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(UnitKerja::class, 'parent_id')->with(['children', 'jabatans']);
    }

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }
}
