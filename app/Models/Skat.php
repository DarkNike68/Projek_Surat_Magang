<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skat extends Model
{
    use HasFactory;
    protected $fillable = ['rak_id', 'name'];

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'rak_id');
    }

    // Relasi ke Outner
    public function outners()
    {
        return $this->hasMany(Outner::class, 'skat_id');
    }
}
