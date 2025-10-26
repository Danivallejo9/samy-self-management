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
      'TIPO_DOC  AS Fac_rec', // PENDIENTE CAMBIO
      'FACTURA AS Consecutivo',
      DB::raw("'' AS tipocred"),//PENDIENTE CAMBIO
      DB::raw("CONVERT (VARCHAR(16), FECHA, 111) AS FEmision"),
      DB::raw("CONVERT (VARCHAR(16), FECHA_VENCE, 111) AS FVence"),
      'COND_PAGO AS cond_pago',
      'DIAS_VENCIDO AS DAtraso',
      'SALDO AS saldo',
      'VLR_DOCTO AS Valor',
      'LIMITE_CREDITO AS limite',
      'VLR_ABONOS AS valorpago',
      DB::raw("'' AS Fpago"), //PENDIENTE CAMBIO
      DB::raw("'' AS credito"), //PENDIENTE CAMBIO
      'COND_PAGO AS Plazo'
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