<?php

namespace App\Models\Wallet\Interfaces;

use Illuminate\Http\JsonResponse;
use stdClass;

interface WalletInterface
{
  public function getInvoices(string $idClient): JsonResponse;
  public function getClientWallet(string $idClient): ?stdClass;
}
