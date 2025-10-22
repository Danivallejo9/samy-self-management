<?php

namespace App\Models\Invoice\Repositories;

use App\Models\Invoice\Interfaces\InvoiceInterface;
use App\Models\Invoice\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class InvoiceRepository implements InvoiceInterface
{
  // public function getInvoices(string $client): array
  // {
  //   $invoices = Invoice::select(
  //     'TIPO AS Fac_rec',
  //     'DOCUMENTO AS Consecutivo',
  //     'TIPO_CREDITO AS tipocred',
  //     DB::raw("CONVERT (VARCHAR(16), FECHA, 111) AS FEmision"),
  //     DB::raw("CONVERT (VARCHAR(16), FECHA_VENCE, 111) AS FVence"),
  //     'CONDICION_PAGO AS cond_pago',
  //     'DIAS_VENCIDO AS DAtraso',
  //     'SALDO AS saldo',
  //     'MONTO AS Valor',
  //     'LIMITE_CREDITO AS limite',
  //     'VALOR_PAGO AS valorpago',
  //     DB::raw("CONVERT (VARCHAR(16), FECHAPAGO, 111) AS Fpago"),
  //     'CREDITO AS credito',
  //     'CONDICION_PAGO AS Plazo'
  //   )
  //     ->where('CLIENTE', $client)
  //     ->get()
  //     ->toArray();

  //   return $invoices;
  // }

    public function getInvoices(string $client): array
  {
    $invoices = Invoice::select(
      'CA.TIPO_DOC  AS Fac_rec', // PENDIENTE CAMBIO
      'CA.FACTURA AS Consecutivo',
      '"" AS tipocred', //PENDIENTE CAMBIO
      DB::raw("CONVERT (VARCHAR(16), FECHA, 111) AS FEmision"),
      DB::raw("CONVERT (VARCHAR(16), FECHA_VENCE, 111) AS FVence"),
      'CA.COND_PAGO AS cond_pago',
      'CA.DIAS_VENCIDO AS DAtraso',
      'CA.SALDO AS saldo', 
      'CA.VLR_DOCTO AS Valor', //PENDIENTE CAMBIO
      'CA.LIMITE_CREDITO AS limite',
      'CA.VLR_ABONOS AS valorpago', //PENDIENTE CAMBIO
      DB::raw("CONVERT (VARCHAR(16), FECHAPAGO, 111) AS Fpago"), //PENDIENTE CAMBIO
      '"" AS credito', //PENDIENTE CAMBIO
      'CA.COND_PAGO AS Plazo'
    )
      ->where('CLIENTE', $client)
      ->get()
      ->toArray();

    return $invoices;
  }
}



// $invoices = Invoice::select(
//   'TIPO AS Fac_rec',
//   'DOCUMENTO AS Consecutivo',
//   'TIPO_CREDITO AS tipocred',
//   DB::raw("CONVERT (VARCHAR(16), FECHA, 111) AS FEmision"),
//   DB::raw("CONVERT (VARCHAR(16), FECHA_VENCE, 111) AS FVence"),
//   'CONDICION_PAGO AS cond_pago',
//   'DIAS_VENCIDO AS DAtraso',
//   'SALDO AS saldo',
//   'MONTO AS Valor',
//   'LIMITE_CREDITO AS limite',
//   'VALOR_PAGO AS valorpago',
//   DB::raw("CONVERT (VARCHAR(16), FECHAPAGO, 111) AS Fpago"),
//   'CREDITO AS credito',
//   'CONDICION_PAGO AS Plazo'
// )