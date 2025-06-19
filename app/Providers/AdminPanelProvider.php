<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class AdminPanelProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::getPanel()->userName(fn ($user) => $user->username ?? $user->name ?? 'Admin');
        });
    }

    public function register(): void
    {
        //
    }
}
