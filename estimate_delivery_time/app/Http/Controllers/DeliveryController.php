<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Services\DeliveryService;

class DeliveryController extends Controller
{
    private $request;
    private $response;
    private $deliveryService;

    public function __construct(
        Request $request,
        ResponseFactory $response,
        DeliveryService $deliveryService
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->deliveryService = $deliveryService;
    }

    public function __invoke(int $zipCode): JsonResponse
    {
        return $this->response->json($this->deliveryService->estimateDeliveryTime($zipCode));
    }
}
