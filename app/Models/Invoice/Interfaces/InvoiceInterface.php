<?php

namespace App\Models\Invoice\Interfaces;

use stdClass;

interface InvoiceInterface
{
  public function getInvoices(string $client): array;
}
