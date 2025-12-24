<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $connection = 'mysql_cyber';
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';

    protected $guarded = [];
}
