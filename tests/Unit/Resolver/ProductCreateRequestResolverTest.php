<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use BoundaryWS\Resolver\ProductCreateRequestResolver;
use BoundaryWS\Exception\ApiException;
use Slim\Http\Request;

class ProductCreateRequestResolverTest extends TestCase {
    /**
     * Subject under test
     *
     * @var ProductCreateRequestResolver
     */
    private $subject;

    public function setUp()
    {
        $this->subject = new ProductCreateRequestResolver();
    }

    public function testCreateMissingDataField(): void
    {
        $this->expectException(ApiException::class);

        $body = [];

        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($body);

        try {
            $this->subject->resolve($request);
        } catch (ApiException $e) {
            $errors = $e->getErrors();

            $this->assertCount(1, $errors);
            $this->assertEquals('ERR-1', $errors[0]->getErrorCode());
            $this->assertEquals('Required Field', $errors[0]->getTitle());
            $this->assertEquals('Required field \'data\' is missing.', $errors[0]->getDetail());
            $this->assertEquals(400, $errors[0]->getStatus());
            $this->assertEquals(['pointer' => '/'], $errors[0]->getSource());

            throw $e;
        }
    }

    public function testCreateMissingDataFields(): void
    {
        $this->expectException(ApiException::class);

        $body = ['data' => []];

        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($body);

        try {
            $this->subject->resolve($request);
        } catch (ApiException $e) {
            $errors = $e->getErrors();

            $this->assertCount(2, $errors);

            $details = [
                'Required field \'display_name\' is missing.',
                'Required field \'cost\' is missing.',
            ];

            foreach ($errors as $error) {
                $this->assertEquals('ERR-1', $error->getErrorCode());
                $this->assertEquals('Required Field', $error->getTitle());
                $this->assertEquals(400, $error->getStatus());
                $this->assertTrue(in_array($error->getDetail(), $details));
                $this->assertEquals(['pointer' => '/data'], $error->getSource());
            }

            throw $e;
        }
    }

    public function testCreateEmptyDataFields(): void
    {
        $this->expectException(ApiException::class);

        $body = [
            'data' => [
                'display_name' => "",
                'cost'         => "",
            ]
        ];

        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($body);

        try {
            $this->subject->resolve($request);
        } catch (ApiException $e) {
            $errors = $e->getErrors();

            $this->assertCount(2, $errors);

            $details = [
                'Required field \'display_name\' is empty.',
                'Required field \'cost\' is empty.',
            ];

            $pointers = [
                '/data/display_name',
                '/data/cost',
            ];

            foreach ($errors as $error) {
                $this->assertEquals('ERR-2', $error->getErrorCode());
                $this->assertEquals('Required Field', $error->getTitle());
                $this->assertEquals(400, $error->getStatus());
                $this->assertTrue(in_array($error->getDetail(), $details));
                $this->assertTrue(in_array($error->getSource()['pointer'], $pointers));
            }

            throw $e;
        }
    }

    /**
     * @dataProvider invalidCostDataProvider
     *
     * @param string $cost
     * @return void
     */
    public function testCreateInvalidCost(string $cost): void
    {
        $this->expectException(ApiException::class);

        $body = [
            'data' => [
                'display_name' => "PRODUCT 1",
                'cost'         => $cost,
            ]
        ];

        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($body);

        try {
            $this->subject->resolve($request);
        } catch (ApiException $e) {
            $errors = $e->getErrors();

            $this->assertCount(1, $errors);
            $this->assertEquals('ERR-3', $errors[0]->getErrorCode());
            $this->assertEquals('Invalid Format', $errors[0]->getTitle());
            $this->assertEquals('Field \'cost\' has an invalid format.', $errors[0]->getDetail());
            $this->assertEquals(400, $errors[0]->getStatus());
            $this->assertEquals(['pointer' => '/data/cost'], $errors[0]->getSource());

            throw $e;
        }
    }

    public function invalidCostDataProvider(): array
    {
        return [
            ["1w"],
            ["1.04e-7"],
            ["-0.0"],
            ["1.999"],
            ["£1.99"],
        ];
    }
}