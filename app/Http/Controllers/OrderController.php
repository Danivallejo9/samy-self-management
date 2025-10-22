<?php

namespace App\Http\Controllers;

use App\Models\Order\Interfaces\OrderInterface;
use App\Models\Order\Order;
use App\Shared\Helpers\Encrypt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private $repository;

    public function __construct(OrderInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getOrdersByClient(): JsonResponse
    {
        $token = request('token');
        if ($token == null) {
            return response()->json([
                'status' => 401,
                'message' => 'Debes de ingresar un cliente codificado'
            ]);
        }

        $client = Encrypt::secured_decrypt(request('token'));
        return $this->repository->getOrdersByClient($client);
    }

    public function find(string $order): JsonResponse
    {
        // dd($order);
        return $this->repository->find($order);
    }

    public function getOrderDetail(string $order): JsonResponse
    {
        return $this->repository->getOrderDetail($order);
    }
}
