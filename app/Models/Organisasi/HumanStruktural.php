<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini
use App\Models\Organisasi\Jabatan;

class HumanStruktural extends Model
{
    protected $connection = 'mysql_simpeg';
    protected $table = 'human_struk_riwayats';
    protected $primaryKey = 'id_humanstruk';
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'kode_jbts');
    }
}