<?php

namespace BoundaryWS\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DataWrapperMiddleware {
    /** 
     * Wraps success responses with data key
     * 
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if (false === in_array($response->getStatusCode(), [200, 201])) {
            return $response;
        }
        $response = $next($request, $response);

        $streamBody = $response->getBody();
        $streamBody->rewind();
        $data = $streamBody->getContents();
        
        return $response->withJson(['data' => json_decode($data)]);
    }
}