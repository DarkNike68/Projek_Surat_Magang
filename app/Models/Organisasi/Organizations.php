<?php

namespace App\Models\Organisasi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini
use App\Models\Organisasi\Jabatan;

class Organizations extends Model
{
    protected $connection = 'mysql_pur';
    protected $table = 'organizations';
    protected $primaryKey = 'org_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function jabatans(): HasMany // Gunakan nama plural untuk hasMany
    {
        // Relasi ini menghubungkan 'org_id' di tabel ini
        // dengan 'id_org' di tabel 'tm_jabatanstruktural'.
        return $this->hasMany(Jabatan::class, 'id_org', 'org_id');
    }
}