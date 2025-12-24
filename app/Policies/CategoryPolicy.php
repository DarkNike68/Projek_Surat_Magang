<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Beri akses tak terbatas untuk role 'BAU'.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'BAU' || $user->role === 'admin') { // Langsung cek kolom 'role'
            return true;
        }
        return null;
    }
    /**
     * Aturan untuk melihat kategori.
     */
    public function view(User $user, Category $category): bool
    {
        // Izinkan jika user punya izin 'view documents'
        return true;
    }

    /**
     * Aturan untuk memodifikasi kategori/dokumen.
     */
    public function update(User $user, Category $category): bool
    {
        // Izinkan jika user punya izin 'edit documents'
        return $user->can('edit documents');
    }
    public function viewPermissionMatrix(User $user): bool
    {
        return $user->can('edit documents');
    }

}