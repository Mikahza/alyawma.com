<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        $this->configureDefaults();
        $this->configureAuthorization();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        // Left off while the application is pre-1.0: every deployment rebuilds the
        // schema with `migrate:fresh`, and no third party holds data yet. Turn this
        // back on the day production data becomes worth keeping.
        DB::prohibitDestructiveCommands(false);

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

    /**
     * Configure the abilities reserved to an administrator.
     */
    protected function configureAuthorization(): void
    {
        Gate::define(
            'manage-shared-catalogue',
            fn (User $user): bool => $user->isAdministrator(),
        );
    }
}
