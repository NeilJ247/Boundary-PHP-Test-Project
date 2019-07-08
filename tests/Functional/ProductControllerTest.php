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

    /**
     * @dataProvider invalidDataProvider
     *
     * @param array $requestData
     */
    public function testPostActionErrors(array $requestData): void
    {
        $data = [
            'data' => $requestData
        ];
        $response = $this->runApp('POST', '/api/products', $data);

        $this->assertEquals(StatusCode::HTTP_BAD_REQUEST, $response->getStatusCode());

        ["errors" => $errors] = json_decode($response->getBody(), true);

        $expectedKeys = [
            'id',
            'status',
            'code',
            'title',
            'detail',
            'source',
        ];

        foreach ($errors as $error) {
            $keys = array_keys($error);
            $this->assertSame($expectedKeys, $keys);
        }
    }

    public function invalidDataProvider(): array
    {
        return [
            [
                ["display_name" => "", "cost" => "1.00"],
            ],
            [
                ["display_name" => "PROD 1", "cost" => ""],
            ],
            [
                ["display_name" => "", "cost" => ""],
            ],
            [
                ["display_name" => null, "cost" => null],
            ],
            [
                ["display_name" => "PROD 1", "cost" => "1.e"],
            ],
        ];
    }
}
