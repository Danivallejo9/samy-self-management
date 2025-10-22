<?php

namespace App\Http\Controllers;

use App\Models\ContactClient\Interfaces\ContactClientInterface;
use App\Shared\Helpers\Encrypt;
use Illuminate\Http\JsonResponse;

// use App\Models\ContactClient\Repositories\ContactClientRepository;

class ContactClientController extends Controller
{

    private $repository;

    public function __construct(ContactClientInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getClients(): JsonResponse
    {
        $mobile = Encrypt::secured_decrypt(request('token'));

        if (is_array($mobile)) {
            return response()->json($mobile, 401);
        }

        return $this->repository->getClients($mobile);
    }
}
