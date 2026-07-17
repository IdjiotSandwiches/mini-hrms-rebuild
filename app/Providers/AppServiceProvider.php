<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole(RoleEnum::ADMIN)) {
                return true;
            }
        });

        Gate::define('attendance', fn (User $user) => $user->hasRole(RoleEnum::AUTH));
        Gate::define('admin', fn (User $user) => $user->hasRole(RoleEnum::ADMIN));

        // if ($this->app->environment('production') || env('APP_ENV') === 'production') {
        //     URL::forceScheme('https');
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
