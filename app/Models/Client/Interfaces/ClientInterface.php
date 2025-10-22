<?php

namespace App\Models\Client\Interfaces;

use App\Models\Client\Client;
use Illuminate\Http\JsonResponse;
// use stdClass;

interface ClientInterface
{
  public function getClient(string $client, string $invoice): JsonResponse;
  public function getClientDataByMobile(int $mobile): ?Client;
}
