<?php

namespace App\Http\Controllers;

use App\Models\Wallet\Interfaces\WalletInterface;
use App\Shared\Helpers\Encrypt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Matcher\Any;

class WalletController extends Controller
{

    private $repository;

    public function __construct(WalletInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getClientWallet(): JsonResponse
    {
        $client = Encrypt::secured_decrypt(request('token'));

        if (is_array($client)) {
            return response()->json($client, 401);
        }

        $response = $this->repository->getClientWallet($client);
        // dd($client);

        if (!$response) {
            return response()->json(['message' => 'No tienes facturas pendientes', 'status' => 404], 404);
        }

        return response()->json($response, 200);
    }

    public function getInvoices()
    {
        $token = request('token');
        $iv = request('iv');

        $data = ['token' => $token, 'iv' => $iv];

        return $this->repository->getInvoices(Encrypt::decrypParams($data));
    }
}
