<?php

namespace App\Models\Client\Repositories;

use App\Models\Client\Client;
use App\Models\Client\Interfaces\ClientInterface;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use stdClass;

final class ClientRepository implements ClientInterface
{

  // private $invoiceId;

  // public function getClient(string $client, string $invoice): JsonResponse
  // {
  //   // $this->invoiceId = $invoice;

  //   $client  = Client::select(
  //     'SAMY_ACTIVITY.dbo.Convenios.DescFinanciero',
  //     'SAMY_ACTIVITY.dbo.Convenios.DescComercial',
  //   )
  //     ->join('samy.GBI_CARTERA', function (JoinClause $join) use ($invoice) {
  //       $join->on('samy.GBI_CARTERA.CLIENTE', '=', 'samy.CLIENTE.CLIENTE')
  //         ->where('samy.GBI_CARTERA.DOCUMENTO', '=', $invoice);
  //     })
  //     ->leftJoin('SAMY_ACTIVITY.dbo.Convenios', 'SAMY_ACTIVITY.dbo.Convenios.ClienteId', '=', 'samy.CLIENTE.CLIENTE')
  //     ->where('SAMY_ACTIVITY.dbo.Convenios.ClienteId', $client)
  //     ->orderBy('SAMY_ACTIVITY.dbo.Convenios.CreatedDate', 'DESC')
  //     ->limit(1)
  //     ->first();

  //   return response()->json($client);
  // }

  public function getClient(string $client, string $invoice): JsonResponse
  {
    // $this->invoiceId = $invoice;

    $client  = Client::select(
      'SAMY_GBI.dbo.convenios.desc_financiero AS DescFinanciero',
      'SAMY_GBI.dbo.convenios.desc_comercial AS DescComercial',
    )
      ->join('UnoEE.dbo.VWS_GBICARTERA', function (JoinClause $join) use ($invoice) {
        $join->on('UnoEE.dbo.VWS_GBICARTERA.CLIENTE', '=', 'UnoEE.dbo.VWS_GBICLIENTES.CLIENTE')
          ->where('UnoEE.dbo.VWS_GBICARTERA.FACTURA', '=', $invoice); //ERA DOCUMENTO
      })
      ->leftJoin('SAMY_GBI.dbo.convenios', 'SAMY_GBI.dbo.convenios.cliente', '=', 'UnoEE.dbo.VWS_GBICLIENTES.CLIENTE')
      ->where('SAMY_GBI.dbo.convenios.cliente', $client)
      ->orderBy('SAMY_GBI.dbo.convenios.CreatedDate', 'DESC') 
      ->limit(1)
      ->first();

    return response()->json($client);
  }

  // public function getClientDataByMobile(int $mobile): ?Client
  // {
  //   $client = Client::select(
  //     'samy.CLIENTE.NOMBRE AS nombre',
  //     'samy.CONTACTO_CLIENTE.NOMBRE AS nombre_contacto',
  //     'samy.CONTACTO_CLIENTE.APELLIDOS AS apellido_contacto',
  //     'samy.CARGOS.DESCRIPCION AS cargo',
  //     'samy.CLIENTE.CONDICION_PAGO as condicion_pago',
  //     'samy.CLIENTE.LIMITE_CREDITO as cupo',
  //     'samy.CARGOS.CODIGO AS codigo_cargo',
  //     'samy.COBRADOR.NOMBRE AS nombre_cobrador',
  //     'samy.VENDEDOR.NOMBRE AS nombre_vendedor',
  //     'samy.VENDEDOR.TELEFONO AS cel_vendedor',
  //     'samy.VENDEDOR.U_JEFE_ZONA AS jefe_zona',
  //     'samy.VENDEDOR.U_CEL_JEFEZONA AS cel_jefe',
  //     'samy.COBRADOR.U_CORREO AS correo',
  //     'samy.CLIENTE.CLIENTE AS documento',
  //     'samy.PEDIDO.PEDIDO AS n_pedido',
  //     'Despachos.DocumentosRemesas.FECHA_DOCUMENTO AS fecha_liberacion'
  //   )
  //     ->leftJoin('samy.PEDIDO', 'samy.PEDIDO.CLIENTE', '=', 'samy.CLIENTE.CLIENTE')
  //     ->leftJoin('Despachos.DocumentosRemesas', 'Despachos.DocumentosRemesas.DOCUMENTOORIGEN', '=', 'samy.PEDIDO.PEDIDO')
  //     ->leftJoin('samy.COBRADOR', 'samy.COBRADOR.COBRADOR', '=', 'samy.CLIENTE.COBRADOR')
  //     ->leftJoin('samy.VENDEDOR', 'samy.VENDEDOR.VENDEDOR', '=', 'samy.CLIENTE.VENDEDOR')
  //     ->leftJoin('samy.CONTACTO_CLIENTE', 'samy.CONTACTO_CLIENTE.CLIENTE', '=', 'samy.CLIENTE.CLIENTE')
  //     ->leftJoin('samy.CARGOS', 'samy.CARGOS.CODIGO', '=', 'samy.CONTACTO_CLIENTE.CARGO')
  //     ->where('samy.CONTACTO_CLIENTE.CELULAR', strval($mobile))
  //     ->orderBy('samy.PEDIDO.FECHA_PEDIDO', 'DESC')
  //     ->first();

  //   return $client;
  // }

  public function getClientDataByMobile(int $mobile): ?Client
  {
    $client = Client::select(
      'UnoEE.dbo.VWS_GBICLIENTES.NOMBRE_CLIENTE AS nombre',
      'CC.nombre_c AS nombre_contacto', 
      'CC.apellidos_c AS apellido_contacto', 
      'CC.cargo_c AS cargo', 
      'C.COND_PAGO AS condicion_pago',
      'C.LIMITE_CREDITO AS cupo',
      'CA.ID AS codigo_cargo', // samy.CARGOS.CODIGO AS codigo_cargo
      'CO.NOMBRE AS Nombre_Cobrador',
      'V.NOMBRE AS nombre_vendedor',
      'V.CELULAR_VENDEDOR AS cel_vendedor',
      'V.NOMBRE_JEFE AS jefe_zona',
      'V.CEL_JEFEZONA AS cel_jefe',
      'CO.CORREO AS correo',
      'UnoEE.dbo.VWS_GBICLIENTES.CLIENTE AS documento',
      'P.PEDIDO_SIESA AS n_pedido',
      'D.FechaDocumento AS fecha_liberacion' 
    )
      ->leftJoin('UnoEE.dbo.VWS_PEDIDOS AS P', 'P.CLIENTE_SUC', '=', 'UnoEE.dbo.VWS_GBICLIENTES.CLIENTE')
      ->leftJoin('UnoEE.dbo.TIC_DOCUMENTOSREMESAS AS D', 'D.PedidoId', '=', 'P.PEDIDO_SIESA')
      ->leftJoin('UnoEE.dbo.VWS_GBICOBRADOR AS CO', 'CO.CODIGO_COBRADOR', '=', 'UnoEE.dbo.VWS_GBICLIENTES.COD_COBRADOR')
      ->leftJoin('UnoEE.dbo.VWS_GBIVENDEDORES AS V', 'V.VENDEDOR', '=', 'UnoEE.dbo.VWS_GBICLIENTES.COD_VENDEDOR')
      ->leftJoin('SAMY_GBI.dbo.gbi_contactoscliente_cstm AS CC', 'CC.documento_cliente_c', '=', 'UnoEE.dbo.VWS_GBICLIENTES.CLIENTE')
      ->leftJoin('SAMY_GBI.dbo.cargos AS CA', 'CA.ID', '=', 'CC.cargo_c')
      ->leftJoin('UnoEE.dbo.VWS_GBICARTERA AS C', 'C.CLIENTE', '=', 'UnoEE.dbo.VWS_GBICLIENTES.CLIENTE')
      ->where('CC.celular_c', strval($mobile))
      ->orderBy('P.FECHA_PEDIDO', 'DESC')
      ->first();

    return $client;
  }
}
