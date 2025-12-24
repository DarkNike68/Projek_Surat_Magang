<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 */
class LetterCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
    ];

    public function getTypeAttribute($value) 
    {
        return ucwords(str_replace('_', ' ', $value));
    }

    public function suratAsJabatan()
    {
        return $this->hasMany(Surat::class, 'letter_code_jabatan_id');
    }
    public function suratAsJenis()
    {
        return $this->hasMany(Surat::class, 'letter_code_jenis_surat_id');
    }
}
