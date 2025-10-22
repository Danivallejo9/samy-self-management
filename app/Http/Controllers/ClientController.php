<?php

namespace App\Http\Controllers;

use App\Models\Client\Interfaces\ClientInterface;
use App\Shared\Helpers\Encrypt;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{

    private $repository;

    public function __construct(ClientInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getInfoCashReceipt(string $client, string $invoce): JsonResponse
    {
        return $this->repository->getClient($client, $invoce);
    }

    public function getClientDataByMobile(int $mobile): JsonResponse
    {

        $response = $this->repository->getClientDataByMobile($mobile);
        if (!$response) {
            return response()->json([
                "status" => 404,
                "message" => "No se encontró un cliente registrado con ese número de celular"
            ], 404);
        }
        return response()->json(
            array_merge(
                $response->toArray(),
                ['encrypt' => Encrypt::secure_encrypt($mobile)]
            ),
            200
        );
    }
}
