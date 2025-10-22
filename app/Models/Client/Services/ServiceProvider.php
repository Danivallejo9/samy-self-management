<?php

namespace App\Models\Client\Services;

use Illuminate\Support\ServiceProvider as SP;
use App\Models\Client\Interfaces\ClientInterface;
use App\Models\Client\Repositories\ClientRepository;

final class ServiceProvider extends SP
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(
      ClientInterface::class,
      ClientRepository::class
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
