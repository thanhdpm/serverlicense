<?php

namespace App\Providers;

use App\Models\Customer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $isProduction = $this->app->isProduction();

        // Surface lazy loading (N+1), silently discarded attributes and typos
        // while developing; never break a production request over them.
        Model::shouldBeStrict(! $isProduction);
        DB::prohibitDestructiveCommands($isProduction);

        URL::forceHttps((bool) config('app.force_https'));
        Paginator::useBootstrap();
        Password::defaults(fn () => Password::min(8));

        $this->configureRateLimiting();

        View::composer('layouts.app', fn ($view) => $view->with('customerCount', Customer::cachedCount()));
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute((int) config('app.api_rate_limit'))
            ->by($request->ip()));
    }
}
