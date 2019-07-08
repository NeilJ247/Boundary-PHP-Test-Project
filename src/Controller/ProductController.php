<?php

namespace BoundaryWS\Controller;

use BoundaryWS\Service\ProductService;
use Slim\Http\Request;
use Slim\Http\Response;
use BoundaryWS\Resolver\ProductCreateRequestResolver;
use Slim\Http\StatusCode;

class ProductController
{
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var ProductCreateRequestResolver
     */
    private $productCreateRequestResolver;

    /**
     * ProductController constructor.
     *
     * @param ProductService               $productService
     * @param ProductCreateRequestResolver $productCreateRequestResolver
     */
    public function __construct(
        ProductService $productService,
        ProductCreateRequestResolver $productCreateRequestResolver
    ) {
        $this->productService = $productService;
        $this->productCreateRequestResolver = $productCreateRequestResolver;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function listAction(Request $request, Response $response) {
        $productQueryResults = $this->productService->getProducts();

        return $response->withJson($productQueryResults);
    }

    /**
     * Create a product
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function postAction(Request $request, Response $response) {
        $data = $this->productCreateRequestResolver->resolve($request);

        $this->productService
            ->createProduct($data['data']['display_name'], $data['data']['cost']);

        return $response->withStatus(StatusCode::HTTP_CREATED);
    }
}