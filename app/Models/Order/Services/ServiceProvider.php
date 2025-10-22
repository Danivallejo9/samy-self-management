<?php

namespace App\Models\Order\Services;

use Illuminate\Support\ServiceProvider as SP;
use App\Models\Order\Interfaces\OrderInterface;
use App\Models\Order\Repositories\OrderRepository;

final class ServiceProvider extends SP
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(
      OrderInterface::class,
      OrderRepository::class
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
