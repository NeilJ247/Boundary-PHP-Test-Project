<?php

namespace BoundaryWS\Controller;

use BoundaryWS\Service\PurchaseService;
use Slim\Http\Request;
use Slim\Http\Response;

class PurchaseController
{
    /**
     * @var PurchaseService
     */
    private $purchaseService;

    /**
     * ProductController constructor.
     *
     * @param PurchaseService $purchaseService
     */
    public function __construct(
        PurchaseService $purchaseService
    ) {
        $this->purchaseService = $purchaseService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function listAction(Request $request, Response $response): Response
    {
        $purchaseQueryResults = $this->purchaseService->getPurchases();

        return $response->withJson($purchaseQueryResults);
    }
}