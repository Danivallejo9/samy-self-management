<?php

namespace App\Models\ContactClient\Services;

use Illuminate\Support\ServiceProvider as SP;
use App\Models\ContactClient\Interfaces\ContactClientInterface;
use App\Models\ContactClient\Repositories\ContactClientRepository;

final class ServiceProvider extends SP
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(
      ContactClientInterface::class,
      ContactClientRepository::class
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
