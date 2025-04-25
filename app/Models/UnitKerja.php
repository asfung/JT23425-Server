<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['label'];

    public function children()
    {
        return $this->hasMany(UnitKerja::class, 'parent_id')->with(['children', 'jabatans']);
    }

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }

    public function parent()
    {
        return $this->belongsTo(UnitKerja::class, 'parent_id');
    }

    public function getLabelAttribute()
    {
        if ($this->parent_id && $this->parent) {
            // return "{$this->name} - {$this->parent->name}";
            return "{$this->parent->name} - {$this->name}";
        }
        return $this->name;
    }
}
