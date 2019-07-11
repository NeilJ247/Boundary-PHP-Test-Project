<?php

namespace BoundaryWS\Controller;

use BoundaryWS\Resolver\PurchasesResponseResolver;
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
     * @var PurchasesResponseResolver
     */
    private $purchasesResponseResolver;

    /**
     * ProductController constructor.
     *
     * @param PurchaseService           $purchaseService
     * @param PurchasesResponseResolver $purchasesResponseResolver
     */
    public function __construct(
        PurchaseService $purchaseService,
        PurchasesResponseResolver $purchasesResponseResolver
    ) {
        $this->purchaseService = $purchaseService;
        $this->purchasesResponseResolver = $purchasesResponseResolver;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function listAction(Request $request, Response $response): Response
    {
        $purchaseQueryResults = $this
            ->purchasesResponseResolver
            ->resolve($this->purchaseService->getPurchases());

        return $response->withJson($purchaseQueryResults);
    }
}
