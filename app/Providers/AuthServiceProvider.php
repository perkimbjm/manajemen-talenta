<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
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
    // Implicitly grant "Super Admin" role all permissions
    // This works in the app by using gate-related functions like auth()->user->can() and @can()
    Gate::before(function ($user) {
      if ($user->hasRole('Super Admin')) {
        return true;
      }

      if ($user->hasPermissionTo('Root Access')) {
        return true;
      }

      // return ($user->hasRole('Super Admin') || $user->can('Root Access')) ? true : null;

      return null;
    });
  }
}
