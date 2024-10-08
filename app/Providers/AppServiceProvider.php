<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // $superAdmin = User::where('username', 'admin')->first();

        // if (!$superAdmin) {
        //     // If not found, create the default Super Admin
        //     User::create([
        //         'name' => 'Super Admin',
        //         'username' => 'admin',
        //         'email' => 'grandeurv2@gmail.com',
        //         'password' => Hash::make('admin123'),
        //         'level' => 'Super Admin',
        //         'created_at' => now('Asia/Manila'),
        //         'updated_at' => now('Asia/Manila'),
        //     ]);
        // }
    }
}
