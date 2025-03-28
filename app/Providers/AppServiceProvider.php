<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\VIP\Candidate;
use App\Observers\CandidateObserver;
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
        view()->share('setting', Setting::first());
        Candidate::observe(CandidateObserver::class);
    }
}
