<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_surat',
        'nomor_urut_per_tahun',
        'tahun',
        'bulan_romawi',
        'letter_code_jenis_surat_id',
        'letter_code_jabatan_id',
        'perihal',
        'user_id',
        'file_path',
        'catatan',
        'status',
        'outner_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

    public function jabatan()
    {
        return $this->belongsTo(LetterCode::class, 'letter_code_jabatan_id');
    }

    public function jenisSurat()
    {
        return $this->belongsTo(LetterCode::class, 'letter_code_jenis_surat_id');
    }

    public function outner()
    {
        return $this->belongsTo(Outner::class);
    }
}
