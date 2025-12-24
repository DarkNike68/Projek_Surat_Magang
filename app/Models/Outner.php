<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outner extends Model
{
    use HasFactory;
    protected $fillable = ['skat_id', 'name'];

    public function skat()
    {
        return $this->belongsTo(Skat::class, 'skat_id');
    }

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }
}
