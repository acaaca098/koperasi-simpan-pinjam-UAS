<?php

namespace App\Providers;

use App\Events\AngsuranStatusChanged;
use App\Events\PinjamanStatusChanged;
use App\Listeners\SendAngsuranNotification;
use App\Listeners\SendPinjamanNotification;
use App\Models\Angsuran;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use App\Policies\AngsuranPolicy;
use App\Policies\PinjamanPolicy;
use App\Policies\SimpananPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Event -> Listener (sesuai Component Diagram: "Events and Listeners")
        Event::listen(PinjamanStatusChanged::class, SendPinjamanNotification::class);
        Event::listen(AngsuranStatusChanged::class, SendAngsuranNotification::class);

        // Policy registration (kalau tidak auto-discovered)
        Gate::policy(Pinjaman::class, PinjamanPolicy::class);
        Gate::policy(Angsuran::class, AngsuranPolicy::class);
        Gate::policy(Simpanan::class, SimpananPolicy::class);
    }
}