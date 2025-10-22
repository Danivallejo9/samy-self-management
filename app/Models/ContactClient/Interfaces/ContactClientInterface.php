<?php

namespace App\Models\ContactClient\Interfaces;

use Illuminate\Http\JsonResponse;

interface ContactClientInterface
{
  public function getClients(string $mobile): JsonResponse;
}
