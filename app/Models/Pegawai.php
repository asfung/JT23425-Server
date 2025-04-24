<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    public function media()
    {
        return $this->hasOne(Media::class);
    }
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }
}
