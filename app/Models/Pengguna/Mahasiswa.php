<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $connection = 'mysql_cyber';
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';

    protected $guarded = [];
}
