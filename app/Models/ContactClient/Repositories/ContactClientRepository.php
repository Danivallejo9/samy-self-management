<?php

namespace App\Models\ContactClient\Repositories;

use App\Models\ContactClient\ContactClient;
use App\Models\ContactClient\Interfaces\ContactClientInterface;
use Illuminate\Http\JsonResponse;
use App\Shared\Helpers\Encrypt;

final class ContactClientRepository implements ContactClientInterface
{

  protected $model;

  public function __construct(ContactClient $contactClient)
  {
    $this->model = $contactClient;
  }

  public function getClients(string $mobile): JsonResponse
  {
    $clients = $this->model::select(
      'SAMY_GBI.dbo.gbi_contactoscliente_cstm.documento_cliente_c',
      'C.NOMBRE'
    )
      ->distinct()
      ->join('UnoEE.dbo.VWS_GBICLIENTES as C', 'SAMY_GBI.dbo.gbi_contactoscliente_cstm.documento_cliente_c', '=', 'C.CLIENTE')
      ->where('SAMY_GBI.dbo.gbi_contactoscliente_cstm.celular_c', $mobile)
      ->groupBy('SAMY_GBI.dbo.gbi_contactoscliente_cstm.documento_cliente_c', 'SAMY_GBI.dbo.gbi_contactoscliente_cstm.celular_c', 'C.NOMBRE_CLIENTE')
      ->get()
      ->toArray();
    // $response = [];
    foreach ($clients as $key => $client) {
      $clients[$key]['CLIENTE'] = Encrypt::secure_encrypt($client['CLIENTE']);
    }

    return response()->json($clients);
  }
}
