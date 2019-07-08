<?php

namespace Tests\Functional;

use Slim\Http\StatusCode;

class ProductControllerTest extends BaseTestCase
{
    public function testListAction(): void
    {
        $response = $this->runApp('GET', '/api/products');

        $body = json_decode($response->getBody(), true);

        $this->assertEquals(StatusCode::HTTP_OK, $response->getStatusCode());
        $this->assertTrue(isset($body['data']));

        foreach ($body['data'] as $product) {
            $this->assertNotEmpty($product['id']);
            $this->assertNotEmpty($product['display_name']);
            $this->assertNotEmpty($product['cost']);
        }
    }

    public function testPostAction(): void
    {
        $data = [
            'data' => [
                'cost'         => '1.00',
                'display_name' => 'PROD 1',
            ]
        ];
        $response = $this->runApp('POST', '/api/products', $data);

        $this->assertEquals(StatusCode::HTTP_CREATED, $response->getStatusCode());
    }
}
