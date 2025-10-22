<?php

namespace App\Models\Wallet\Services;

use Illuminate\Support\ServiceProvider as SP;
use App\Models\Wallet\Interfaces\WalletInterface;
use App\Models\Wallet\Repositories\WalletRepository;

final class ServiceProvider extends SP
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(
      WalletInterface::class,
      WalletRepository::class
    );
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}
