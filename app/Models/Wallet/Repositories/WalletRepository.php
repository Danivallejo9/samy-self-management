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
      'TIPO_DOC  AS Fac_rec', // PENDIENTE CAMBIO
      'FACTURA AS Consecutivo',
      DB::raw("'' AS tipocred"), //PENDIENTE CAMBIO
      DB::raw("CONVERT (VARCHAR(16), FECHA, 111) AS FEmision"),
      DB::raw("CONVERT (VARCHAR(16), FECHA_VENCE, 111) AS FVence"),
      'COND_PAGO AS cond_pago',
      'DIAS_VENCIDO AS DAtraso',
      'SALDO AS saldo', 
      DB::raw("'' AS Valor"), //PENDIENTE CAMBIO
      'LIMITE_CREDITO AS limite',
      DB::raw("'' AS valorpago"), //PENDIENTE CAMBIO
      DB::raw("'' AS Fpago"), //PENDIENTE CAMBIO
      DB::raw("'' AS credito"), //PENDIENTE CAMBIO
      'COND_PAGO AS Plazo'
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

  //MOSTRAR DATOS PRINCIPALES Y EN VER CARTERA
  public function getClientWallet(string $client): ?stdClass
  {
    // $this->client = $client;
    $caSum = DB::table('UnoEE.dbo.VWS_GBICARTERA')
        ->select(
            'CLIENTE',
            DB::raw('SUM(SALDO) AS deuda'),
            DB::raw('MAX(DIAS_VENCIDO) AS dia_atraso')
        )
        ->groupBy('CLIENTE');

    $clientWallet = DB::table('UnoEE.dbo.VWS_GBIESTADOCLIENTE AS EC')
        ->select(
            'EC.LIMITE_CREDITO AS limite',
            'EC.COND_PAGO',
            // deuda desde la subconsulta (COALESCE por si no hay filas en cartera)
            DB::raw('COALESCE(CA_SUM.deuda, 0) AS deuda'),
            'EC.PORAPLICAR AS por_aplicar',
            // cupo_disp calculado según la fórmula: limite - SUM(saldo) + por_aplicar
            DB::raw('COALESCE(EC.LIMITE_CREDITO, 0) - COALESCE(CA_SUM.deuda, 0) + COALESCE(EC.PORAPLICAR, 0) AS cupo_disp'),
            'CL.NOMBRE_CLIENTE AS nombre',
            DB::raw('COALESCE(CA_SUM.dia_atraso, 0) AS dia_atraso'),
            DB::raw('(
                SELECT SUM(CAST(P.TOTAL_PEDIDO AS FLOAT))
                FROM UnoEE.dbo.VWS_PEDIDOS AS P
                WHERE P.CLIENTE_SUC = EC.CLIENTE
                  AND P.ESTADO = 1
            ) AS total_pedido')
        )
        ->leftJoinSub($caSum, 'CA_SUM', function ($join) {
            $join->on('CA_SUM.CLIENTE', '=', 'EC.CLIENTE');
        })
        ->leftJoin('UnoEE.dbo.VWS_GBICLIENTES AS CL', 'CL.CLIENTE', '=', 'EC.CLIENTE')
        ->where('EC.CLIENTE', $client)
        ->first();

    return $clientWallet;
    // WHERE P.CLIENTE_SUC = EC.CLIENTE AND ESTADO = \'N\'   PENDIENTE PORQUE ESTADO NO EXISTE, SON NÚMEROS
  }
}
