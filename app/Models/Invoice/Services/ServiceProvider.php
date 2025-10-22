<?php

namespace App\Models\Invoice\Services;

use Illuminate\Support\ServiceProvider as SP;
use App\Models\Invoice\Interfaces\InvoiceInterface;
use App\Models\Invoice\Repositories\InvoiceRepository;

final class ServiceProvider extends SP
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(
      InvoiceInterface::class,
      InvoiceRepository::class
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
