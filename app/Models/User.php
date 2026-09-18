<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Organisasi\HumanStruktural;
use App\Models\Organisasi\Jabatan;
use App\Models\Pengguna\Dosen;
use App\Models\Pengguna\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserPermissionException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles; 
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use App\Models\Organisasi\Organizations;
use App\Models\Pengguna\Pegawai;

/**
 *
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $connection = 'mysql_cyber';

    protected $primaryKey = 'id_users';
    // public $incrementing = true; // Karena id_users adalah auto-increment
    // protected $keyType = 'int';  // Karena id_users adalah integer

    // protected $with = ['dosen', 'mahasiswa', 'hmnstruktural']; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'unit_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'password_md5', 
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            //'password' => 'hashed',
        ];
    }

    public function isBiroUmum(): bool
    {
        return Cache::remember('is_bau_' . $this->id_users, 600, function () {
        
        // 1. Cari riwayat jabatan terakhir user
        $riwayatJabatan = HumanStruktural::where('id_pengguna', $this->id_users)
                                            ->latest('terhitung_mulai')
                                            ->first();
        if (!$riwayatJabatan) return false;

        // 2. Cari detail jabatan
        $jabatan = Jabatan::find($riwayatJabatan->id_jabatan);
        if (!$jabatan || !$jabatan->id_org) return false;
        
        // 3. Cari detail organisasi
        $organisasi = Organizations::find($jabatan->id_org);
        if (!$organisasi) return false;

        // 4. Periksa nama organisasi
        return $organisasi->name === 'BAU (BADAN ADMINISTRASI UMUM)';
        });
    }

    public function isLptik(): bool
    {
        return Cache::remember('is_lptik_' . $this->id_users, 600, function () {
        
        // 1. Cari riwayat jabatan terakhir user
        $riwayatJabatan = HumanStruktural::where('id_pengguna', $this->id_users)
                                            ->latest('terhitung_mulai')
                                            ->first();
        if (!$riwayatJabatan) return false;

        // 2. Cari detail jabatan
        $jabatan = Jabatan::find($riwayatJabatan->id_jabatan);
        if (!$jabatan || !$jabatan->id_org) return false;
        
        // 3. Cari detail organisasi
        $organisasi = Organizations::find($jabatan->id_org);
        if (!$organisasi) return false;

        // 4. Periksa nama organisasi (SESUAIKAN DENGAN DATABASE)
        return $organisasi->name === 'LPTIK' || $organisasi->name === 'LPTIK (IT)-STAFF'; 
        });
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function permissionExceptions(): HasMany
    {
        return $this->hasMany(UserPermissionException::class);
    }

    public function dosen(): HasOne
    {
        return $this->hasOne(Dosen::class, 'id_users', 'id_users');
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class, 'id_users', 'id_users');
    }

    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'id_users', 'id_users');
    }

    public function getFullNameAttribute(): string
    {
        // Cek apakah user ini punya profil dosen
        if ($this->dosen) {
            return $this->dosen->nama_dosen;
        }
        // Jika tidak, cek apakah dia mahasiswa
        if ($this->mahasiswa) {
            return $this->mahasiswa->nama_mahasiswa;
        }
        // Jika tidak, cek apakah dia pegawai
        if ($this->pegawai) {
            return $this->pegawai->nama_pegawai;
        }

        // Jika tidak punya profil sama sekali, kembalikan username
        return $this->username;
    }

    public function riwayatJabatanTerakhir(): HasOne
    {
        return $this->hasOne(HumanStruktural::class, 'id_pengguna', 'id_users')->latest('terhitung_mulai');
    }

}
