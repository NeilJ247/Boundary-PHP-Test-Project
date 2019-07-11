<?php

namespace BoundaryWS\Handler;

use BoundaryWS\Error\ApplicationError;
use BoundaryWS\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiExceptionHandler
{
    /**
     * @param ServerRequestInterface   $request
     * @param ResponseInterface        $response
     * @param \Exception|ApiException  $exception
     *
     * @return mixed
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        \Exception $exception
    ): ResponseInterface {
        if (false === ($exception instanceof ApiException)) {
            return $response;
        }

        $errors = array_map(
            function (ApplicationError $error) {
                return $error->toArray();
            },
            $exception->getErrors()
        );

        $responseData = ['errors' => $errors];

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Accept, Origin, X-Token, Content-Type')
            ->withHeader('Access-Control-Allow-Methods', 'PUT, PATCH, GET, POST, DELETE, OPTIONS')
            ->withStatus($exception->getStatusCode())
            ->withJson($responseData);
    }
}
