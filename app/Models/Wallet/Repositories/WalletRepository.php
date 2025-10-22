<?php

namespace App\Models\Wallet\Repositories;

use App\Models\Wallet\Interfaces\WalletInterface;
use App\Models\Wallet\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use stdClass;

final class WalletRepository implements WalletInterface
{
  // private $client;
  // public function getInvoices(string $idClient): JsonResponse
  // {
  //   $wallet = Wallet::select(
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
  //     ->where('CLIENTE', $idClient)
  //     ->get()
  //     ->toArray();

  //   return response()->json($wallet);
  // }

  public function getInvoices(string $idClient): JsonResponse
  {
    $wallet = Wallet::select(
      'CA.TIPO_DOC  AS Fac_rec', // PENDIENTE CAMBIO
      'CA.FACTURA AS Consecutivo',
      '"" AS tipocred', //PENDIENTE CAMBIO
      DB::raw("CONVERT (VARCHAR(16), FECHA, 111) AS FEmision"),
      DB::raw("CONVERT (VARCHAR(16), FECHA_VENCE, 111) AS FVence"),
      'CA.COND_PAGO AS cond_pago',
      'CA.DIAS_VENCIDO AS DAtraso',
      'CA.SALDO AS saldo', 
      '"" AS Valor', //PENDIENTE CAMBIO
      'CA.LIMITE_CREDITO AS limite',
      '"" AS valorpago', //PENDIENTE CAMBIO
      DB::raw("CONVERT (VARCHAR(16), FECHAPAGO, 111) AS Fpago"), //PENDIENTE CAMBIO
      '"" AS credito', //PENDIENTE CAMBIO
      'CA.COND_PAGO AS Plazo'
    )
      ->where('CLIENTE', $idClient)
      ->get()
      ->toArray();

    return response()->json($wallet);
  }

  // public function getClientWallet(string $client): ?stdClass
  // {
  //   // $this->client = $client;
  //   $clientWallet = DB::table('COSMETICOS_SAMY.samy.GBI_ESTADOCLIENTE as EC')
  //     ->select(
  //       'EC.LIMITE_CREDITO as limite',
  //       'EC.COND_PAGO',
  //       'EC.SALDOPENDIENTE as deuda',
  //       'EC.PORAPLICAR as por_aplicar',
  //       'EC.CUPO_DISPONIBLE as cupo_disp',
  //       'CLI.NOMBRE as nombre',
  //       DB::raw('ISNULL((
  //           SELECT MAX(C.DIAS_VENCIDO)
  //           FROM COSMETICOS_SAMY.samy.GBI_CARTERA C
  //           WHERE C.CLIENTE = EC.CLIENTE
  //       ), 0) as dia_atraso'),
  //       DB::raw('(
  //           SELECT SUM(CAST(TOTAL_PEDIDO AS FLOAT)) AS valor_pedidos
  //           FROM COSMETICOS_SAMY.samy.GBI_PEDIDOS
  //           WHERE CLIENTE = EC.CLIENTE AND ESTADO = \'N\'
  //       ) as total_pedido')
  //     )
  //     ->join('COSMETICOS_SAMY.samy.CLIENTE as CLI', function ($join) use ($client) {
  //       $join->on('CLI.CLIENTE', '=', 'EC.CLIENTE')
  //         ->where('EC.CLIENTE', '=', $client);
  //     })
  //     ->first();

  //   return $clientWallet;
  // }

  public function getClientWallet(string $client): ?stdClass
  {
    // $this->client = $client;
    $clientWallet = DB::table('UnoEE.dbo.VWS_GBIESTADOCLIENTE AS EC')
      ->select(
        'EC.LIMITE_CREDITO AS limite',
        'EC.COND_PAGO',
        'CA.SALDO AS deuda',
        'EC.PORAPLICAR AS por_aplicar',
        'CA.CUPO_DISPONIBLE AS cupo_disp',
        'CL.NOMBRE_CLIENTE AS nombre',
        DB::raw('ISNULL((
            SELECT MAX(CA.DIAS_VENCIDO)
            FROM UnoEE.dbo.VWS_GBICARTERA AS CA
            WHERE CA.CLIENTE = EC.CLIENTE
        ), 0) AS dia_atraso'),
        DB::raw('(
            SELECT SUM(CAST(TOTAL_PEDIDO AS FLOAT)) AS valor_pedidos 
            FROM UnoEE.dbo.VWS_PEDIDOS AS P
            WHERE P.CLIENTE_SUC = EC.CLIENTE AND ESTADO = 1     
        ) AS total_pedido')
      )
      ->join('UnoEE.dbo.VWS_GBICARTERA AS CA', 'CA.CLIENTE', '=', 'EC.CLIENTE')
      ->join('UnoEE.dbo.VWS_GBICLIENTES AS CL', function ($join) use ($client) {
        $join->on('CL.CLIENTE', '=', 'EC.CLIENTE')
          ->where('EC.CLIENTE', '=', $client);
      })
      ->first();

    return $clientWallet;
    // WHERE P.CLIENTE_SUC = EC.CLIENTE AND ESTADO = \'N\'   PENDIENTE PORQUE ESTADO NO EXISTE, SON NÚMEROS
  }
}
