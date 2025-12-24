<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $connection = 'mysql_cyber';
    protected $table = 'pegawai';
    protected $primaryKey = 'id_mahasiswa';
    protected $guarded = [];
}
