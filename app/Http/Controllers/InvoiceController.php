<?php

namespace App\Http\Controllers;

use App\Models\Invoice\Interfaces\InvoiceInterface;
use App\Shared\Helpers\Encrypt;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{

    private $repository;

    public function __construct(InvoiceInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getInvoices(): JsonResponse
    {
        $client = Encrypt::secured_decrypt(request('token'));

        $invoices = $this->repository->getInvoices($client);
        if (!count($invoices)) {
            return response()->json(['message' => 'No tiene facturas en los últimos dos meses', "status" => 404], 404);
        }

        return response()->json($invoices);
    }
}
