<?php

namespace App\Models\Order\Interfaces;

use App\Models\Order\Order;
use Illuminate\Http\JsonResponse;

interface OrderInterface
{
  public function getOrdersByClient(string $idClient): JsonResponse;
  public function find(string $order): JsonResponse;
  public function getOrderDetail(string $order): JsonResponse;
}
