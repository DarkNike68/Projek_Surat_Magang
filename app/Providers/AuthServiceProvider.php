<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\MultiHashUserProvider; // Pastikan ini ada jika Anda masih menggunakannya

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Definisikan Gate untuk admin berdasarkan jabatan di 3 database
        Gate::define('view-admin-menu', function (User $user) {
            return $user->isBiroUmum();
        });

        // Definisikan Gate KHUSUS untuk Super Admin (Hanya LPTIK)
        Gate::define('is-super-admin', function (User $user) {
            return $user->isLptik();
        });

        // Provider untuk login (ini sudah benar, jangan diubah)
        Auth::provider('multi_hash_auth', function ($app, array $config) {
            return new MultiHashUserProvider($app['hash'], $config['model']);
        });
    }
}