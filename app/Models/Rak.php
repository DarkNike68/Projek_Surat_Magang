<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rak extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'year'];
    public function skats()
    {
        return $this->hasMany(Skat::class, 'rak_id');
    }
}
