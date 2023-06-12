<?php

namespace App\Providers;

// use Illuminate\Auth\Access\Gate;
use App\Models\User;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\View\Composers\MenuComposer;
use App\View\Composers\DDLevel;
use App\View\Composers\DDLevelF;
use App\View\Composers\MenuQuickAccess;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Gate::define('Admin', function (User $user) {
            return $user->level === 'Admin';
        });
        Gate::define('1', function (User $user) {
            return $user->level === '1';
        });
        Gate::define('2', function (User $user) {
            return $user->level === '2';
        });
        Gate::define('PCB', function (User $user) {
            return $user->level === 'PCB';
        });

        View::composer(['layouts.backend-Theme-3.menu'], MenuComposer::class);
        View::composer(['layouts.backend-Theme-3.main'], MenuQuickAccess::class);
        View::composer(['Setting.ListMenuModal'], DDLevel::class);
        View::composer(['Setting.UserModall'], DDLevelF::class);
    }
}
