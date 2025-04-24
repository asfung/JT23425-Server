<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }
}
