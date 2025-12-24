<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini
use App\Models\Organisasi\Organizations;

class Jabatan extends Model
{
    protected $connection = 'mysql_simpeg';
    protected $table = 'tm_jabatanstruktural';
    protected $primaryKey = 'kode_jbts';

    public function organization(): BelongsTo 
    {
        return $this->belongsTo(Organizations::class, 'id_org', 'org_id');
    }
}