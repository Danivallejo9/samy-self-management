<?php

namespace App\Models\Order\Repositories;

use App\Models\Order\Interfaces\OrderInterface;
use App\Models\Order\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class OrderRepository implements OrderInterface
{
  // public function getOrdersByClient(string $client): JsonResponse
  // {
  //   $twoMonthsAgo = now()->subMonths(2)->startOfMonth();
  //   $orders = Order::from('samy.PEDIDO as p')
  //     ->select([
  //       DB::raw('c.NOMBRE as name'),
  //       'p.PEDIDO as n_order',
  //       DB::raw("CONCAT(UPPER(FORMAT(p.FECHA_PEDIDO, 'MMMM', 'es-ES')), ' ', DAY(p.FECHA_PEDIDO)) as date_release"),
  //       'p.U_ESTADO as status',
  //       'dr.REMESA as remittance',
  //       DB::raw('CONVERT(date, dr.REMESAFECHAENTREGA) as deliver'),
  //     ])
  //     ->join('samy.CLIENTE as c', function ($join) use ($client) {
  //       $join->on('p.CLIENTE', '=', 'c.CLIENTE')
  //         ->where('p.CLIENTE', $client);
  //     })
  //     ->where('p.FECHA_PEDIDO', '>=', $twoMonthsAgo)
  //     ->where('p.ESTADO', '!=', 'C')
  //     ->where('p.PEDIDO', 'not like', '%POP')
  //     ->leftJoin('Despachos.DocumentosRemesas as dr', 'p.PEDIDO', '=', 'dr.DOCUMENTOORIGEN')
  //     ->orderBy('p.FECHA_PEDIDO', 'ASC')
  //     ->get()
  //     ->toArray();

  //   return response()->json($orders);
  // }

  public function getOrdersByClient(string $client): JsonResponse
  {
    $twoMonthsAgo = now()->subMonths(2)->startOfMonth();
    $orders = Order::from('UnoEE.dbo.VWS_PEDIDOS AS P')
      ->select([
        DB::raw('C.NOMBRE_CLIENTE AS name'),
        'P.PEDIDO_SIESA AS n_order',
        DB::raw("CONCAT(UPPER(FORMAT(P.FECHA_PEDIDO, 'MMMM', 'es-ES')), ' ', DAY(P.FECHA_PEDIDO)) AS date_release"),
        'P.ESTADO AS status',
        'DR.Remesa AS remittance',
        DB::raw('CONVERT(date, DR.RemesaFechaEntrega) AS deliver'),
      ])
      ->join('UnoEE.dbo.VWS_GBICLIENTES AS C', function ($join) use ($client) {
        $join->on('P.CLIENTE_SUC', '=', 'C.CLIENTE')
          ->where('P.CLIENTE_SUC', $client);
      })
      ->where('P.FECHA_PEDIDO', '>=', $twoMonthsAgo)
    //  ->where('P.ESTADO', '!=', 'C') //PREGUNTAR QUE SIGNIFICA ESTADO C
    //  ->where('P.PEDIDO', 'not like', '%POP')
      ->leftJoin('UnoEE.dbo.TIC_DOCUMENTOSREMESAS AS DR', 'P.PEDIDO_SIESA', '=', 'DR.PedidoId')
      ->orderBy('P.FECHA_PEDIDO', 'ASC')
      ->get()
      ->toArray();

    return response()->json($orders);
  }

  // public function find(string $order): JsonResponse
  // {
  //   $order = Order::select(
  //     'samy.CLIENTE.NOMBRE as name',
  //     'samy.PEDIDO.PEDIDO as n_order',
  //     'samy.PEDIDO.U_ESTADO as status',
  //     DB::raw('CONVERT(date, samy.PEDIDO.FECHA_PEDIDO) as release_date'),
  //     DB::raw('CONVERT(date, samy.PEDIDO.FECHA_ORDEN) AS release_order'),
  //     DB::raw('CONVERT(date, samy.PEDIDO.FECHA_PROX_EMBARQU) AS deliver_start'),
  //     DB::raw('CONVERT(date, Despachos.DocumentosRemesas.REMESAFECHAENTREGA) AS deliver_end'),
  //     'Despachos.DocumentosRemesas.REMESA as remittance',
  //     'Despachos.DocumentosRemesas.ESTADO as status_remittance'
  //   )->join('samy.CLIENTE', 'samy.CLIENTE.CLIENTE', '=', 'samy.PEDIDO.CLIENTE')
  //     ->leftJoin('Despachos.DocumentosRemesas', 'Despachos.DocumentosRemesas.DOCUMENTOORIGEN', '=', 'samy.PEDIDO.PEDIDO')
  //     ->where('samy.PEDIDO.PEDIDO', '=', $order)
  //     ->get();

  //   return response()->json($order->toArray());
  // }

  public function find(string $order): JsonResponse
  {
    $order = Order::select(
      'C.NOMBRE_CLIENTE AS nombre',
      'PEDIDO_SIESA AS n_order',
      'ESTADO AS status',
      DB::raw('CONVERT(date, FECHA_PEDIDO) as release_date'),
      DB::raw('CONVERT(date, FECHA_CREACION) AS release_order'), //FECHA_ORDEN no existe en la nueva tabla, se usa FECHA_CREACION
      DB::raw('"" AS deliver_start'), //  DB::raw('CONVERT(date, samy.PEDIDO.FECHA_PROX_EMBARQU) AS deliver_start'),
      DB::raw('CONVERT(date, DR.RemesaFechaEntrega) AS deliver_end'),
      'DR.Remesa as remittance',
      'DR.Estado as status_remittance'
    )->join('UnoEE.dbo.VWS_GBICLIENTES AS C', 'C.CLIENTE', '=', 'CLIENTE_SUC')
      ->leftJoin('UnoEE.dbo.TIC_DOCUMENTOSREMESAS AS DR', 'DR.PedidoId', '=', 'PEDIDO_SIESA')
      ->where('PEDIDO_SIESA', '=', $order)
      ->get();

    return response()->json($order->toArray());
  }

  // public function getOrderDetail(string $order): JsonResponse
  // {
  //   $detailOrder = Order::select(
  //     'samy.PEDIDO.NOMBRE_CLIENTE AS NAME',
  //     'samy.PEDIDO_LINEA.PEDIDO_LINEA AS LINE_ITEM',
  //     'samy.PEDIDO_LINEA.ARTICULO AS ITEM',
  //     'samy.ARTICULO.DESCRIPCION as description',
  //     'samy.ARTICULO.CODIGO_BARRAS_VENT AS BAR_CODE',
  //     'samy.PEDIDO_LINEA.CANTIDAD_PEDIDA AS AMOUNT'
  //   )
  //     ->join('samy.PEDIDO_LINEA', 'samy.PEDIDO_LINEA.PEDIDO', '=', 'samy.PEDIDO.PEDIDO')
  //     ->join('samy.ARTICULO', 'samy.ARTICULO.ARTICULO', '=', 'samy.PEDIDO_LINEA.ARTICULO')
  //     ->where('samy.PEDIDO.PEDIDO', $order)
  //     ->get();

  //   // dd($detailOrder->toSql());
  //   return response()->json($detailOrder->toArray());
  // }

  public function getOrderDetail(string $order): JsonResponse
  {
    $detailOrder = Order::select(
      'NOMBRE AS NAME',
      'PE.ID_LINEA AS LINE_ITEM',
      'PE.ARTICULO AS ITEM',
      'A.DESCRIPCION as description',
      'A.COD_BARRAS AS BAR_CODE',
      'PE.CANTIDAD_PEDIDA AS AMOUNT'
    )
      ->join('UnoEE.dbo.VWS_PEDIDOSDETALLES AS PE', 'PE.PEDIDO', '=', 'PEDIDO_SIESA')
      ->join('UnoEE.dbo.VWS_GBIARTICULOS AS A', 'A.ARTICULO', '=', 'PE.ARTICULO')
      ->where('PEDIDO_SIESA', $order)
      ->get();

    // dd($detailOrder->toSql());
    return response()->json($detailOrder->toArray());
  }
}
