<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class MultiHashUserProvider extends EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
    $plain = $credentials['password'];
    $bcryptHash = $user->getAuthPassword(); // Ambil hash dari kolom 'password'

    // PENGECEKAN BARU:
    // Hanya jalankan verifikasi bcrypt JIKA hash-nya ada (tidak null atau kosong).
    if (!empty($bcryptHash) && $this->hasher->check($plain, $bcryptHash)) {
        return true;
    }

    // Jika pengecekan bcrypt gagal atau dilewati, lanjutkan ke pengecekan md5.
    if (isset($user->password_md5) && md5($plain) === $user->password_md5) {
        return true;
    }

    // Jika keduanya gagal, baru return false.
    return false;
    }   
}